<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Elastica\Query\MatchAll;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Helper\TreeHelper;
use Ojs\CoreBundle\Params\IssueDisplayModes;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\BlockRepository;
use Ojs\JournalBundle\Entity\BoardMember;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueRepository;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Section;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{

    /**
     * Global index page
     * @return Response
     */
    public function indexAction()
    {
        $data['page'] = 'index';

        $em = $this->getDoctrine()->getManager();
        $data['journals'] = $em->getRepository('OjsJournalBundle:Journal')->getHomePageList($this->get('file_cache'));
        shuffle($data['journals']);

        /** @var SubjectRepository $repo */
        $repo = $em->getRepository('OjsJournalBundle:Subject');

        $allSubjects = $repo->findAll();
        usort($allSubjects, function($a, $b) {
            return $b->getRgt() > $a->getRgt();
        });
        $data['subjects'] = TreeHelper::createSubjectTreeView(TreeHelper::SUBJECT_SEARCH, $this->get('router'), $allSubjects);
        $data['page'] = 'index';

        $data['stats'] = [
            'journal' => 0,
            'article' => 0,
            'subject' => 0,
            'publisher' => 0,
            'user' => 0
        ];

        $data['stats']['journal'] = $this->get('fos_elastica.index.search.journal')->count(new MatchAll());
        $data['stats']['article'] = $this->get('fos_elastica.index.search.articles')->count(new MatchAll());
        $data['stats']['subject'] = $this->get('fos_elastica.index.search.subject')->count(new MatchAll());
        $data['stats']['publisher'] = $this->get('fos_elastica.index.search.publisher')->count(new MatchAll());
        $data['stats']['user'] = $this->get('fos_elastica.index.search.user')->count(new MatchAll());

        $data['announcements'] = $em->getRepository('OjsAdminBundle:AdminAnnouncement')->findAll();
        $data['announcement_count'] = count($data['announcements']);
        $data['posts'] = $em->getRepository('OjsAdminBundle:AdminPost')->findAll();

        // anything else is anonym main page
        return $this->render('OjsSiteBundle::Site/home.html.twig', $data);
    }

    public function publisherPageAction($slug)
    {
        $data['page'] = 'organizations';
        $journalService = $this->get('ojs.journal_service');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Publisher')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($entity);
        $data['entity'] = $entity;
        /** @var Journal $journal */
        foreach ($entity->getJournals() as $journal) {
            $journal->setPublicURI($journalService->generateUrl($journal));
        }

        return $this->render('OjsSiteBundle::Publisher/publisher_index.html.twig', $data);
    }

    public function journalArticlesAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $articles = $journal->getArticles();
        $data = [
            'journal' => $journal,
            'articles' => $articles,
            'page' => 'journal',
            'blocks' => $blockRepo->journalBlocks($journal),
        ];

        return $this->render('OjsSiteBundle::Journal/journal_articles.html.twig', $data);
    }

    /**
     * @param $slug
     * @return Response
     */
    public function announcementIndexAction($slug)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['announcements'] = $em->getRepository('OjsJournalBundle:JournalAnnouncement')->findBy(
            ['journal' => $journal],
            ['id' => 'DESC']
        );

        $data['page'] = 'announcement';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/announcement_index.html.twig', $data);
    }

    public function issuePageAction($id)
    {
        /**
         * @var BlockRepository $blockRepo
         * @var IssueRepository $issueRepo
         * @var Issue $issue
         */
        $em = $this->getDoctrine()->getManager();

        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
        $articleRepo = $em->getRepository(Article::class);

        $issue = $issueRepo->find($id);
        $this->throw404IfNotFound($issue);

        $blocks = $blockRepo->journalBlocks($issue->getJournal());

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('issue_view');

        $sections = $this->setupIssueSections($issue);

        $articles = [];

        /** @var Section $section */
        foreach ($sections as $section) {
            $articles[$section->getId()] = $articleRepo->getOrderedArticles($issue, $section);
        }

        $displayModes = [
            'all' => IssueDisplayModes::SHOW_ALL,
            'title' => IssueDisplayModes::SHOW_TITLE,
            'volumeAndNumber' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER,
        ];

        return $this->render(
            'OjsSiteBundle:Issue:detail.html.twig',
            [
                'issue'     => $issue,
                'blocks'    => $blocks,
                'token'     => $token,
                'sections'  => $sections,
                'articles'  => $articles,
                'displayModes' => $displayModes,
            ]
        );
    }

    /**
     * @param Issue $issue
     * @return ArrayCollection
     */
    private function setupIssueSections(Issue $issue)
    {
        $sections = [];
        foreach($issue->getJournal()->getSections() as $section){
            $sectionHaveIssueArticle = false;
            foreach($section->getArticles() as $article){
                if($article->getIssue() !== null){
                    if($article->getIssue()->getId() == $issue->getId()){
                        $sectionHaveIssueArticle = true;
                    }
                }
            }
            if($sectionHaveIssueArticle){
                $sections[] = $section;
            }
        }
        //order sections by section order
        uasort($sections, function($a, $b){
            return ((int)$a->getSectionOrder() > (int)$b->getSectionOrder()) ? 1 : -1;
        });
        return $sections;
    }

    public function journalPageDetailAction($slug, $journal_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        $this->throw404IfNotFound($journal);
        $page = $em->getRepository('OjsJournalBundle:JournalPage')->findOneBy(['journal' => $journal, 'slug' => $slug]);
        $this->throw404IfNotFound($page);

        return $this->render('OjsSiteBundle:Journal:page.html.twig', ['journalPage' => $page]);
    }

    public function journalPostDetailAction($slug, $journal_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        $this->throw404IfNotFound($journal);

        $post = $em->getRepository('OjsJournalBundle:JournalPost')->findOneBy(['journal' => $journal, 'slug' => $slug]);
        $this->throw404IfNotFound($post);


        return $this->render('OjsSiteBundle:Journal:post.html.twig', ['journalPost' => $post]);
    }
}
