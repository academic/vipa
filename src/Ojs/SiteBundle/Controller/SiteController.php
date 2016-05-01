<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Elastica\Query\MatchAll;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\CoreBundle\Helper\TreeHelper;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\BlockRepository;
use Ojs\JournalBundle\Entity\BoardMember;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\IssueRepository;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\JournalBundle\Entity\SubscribeMailList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

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

        $journalApplications = $this
            ->getDoctrine()
            ->getRepository('OjsAdminBundle:SystemSetting')
            ->findOneBy(['name' => 'journal_application']);

        $publisherApplications = $this
            ->getDoctrine()
            ->getRepository('OjsAdminBundle:SystemSetting')
            ->findOneBy(['name' => 'publisher_application']);

        $data['journalApplicationAllowance'] = $journalApplications ? $journalApplications->getValue() : true;
        $data['publisherApplicationAllowance'] = $publisherApplications ? $publisherApplications->getValue() : true;

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


    public function journalIndexAction(Request $request, $publisher, $slug)
    {
        $journalService = $this->get('ojs.journal_service');
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        /** @var \Ojs\JournalBundle\Entity\BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var IssueRepository $issueRepo */
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');

        $publisherEntity = $em->getRepository('OjsJournalBundle:Publisher')->findOneBy(['slug' => $publisher]);
        $this->throw404IfNotFound($publisherEntity);

        /** @var Journal $journal */
        $journal = $journalRepo->findOneBy(['slug' => $slug, 'publisher' => $publisherEntity]);
        $this->throw404IfNotFound($journal);

        //if theme preview is active set given theme
        if(
            $request->query->has('themePreview') &&
            $request->query->has('id') &&
            is_int((int)$request->query->get('id')) &&
            $request->query->has('type')
        ){
            $previewThemeId = $request->query->get('id');
            $themeType = $request->query->get('type');
            if($themeType == 'journal'){
                $previewTheme = $em->getRepository('OjsJournalBundle:JournalTheme')->find($previewThemeId);
            }elseif($themeType == 'global'){
                $previewTheme = $em->getRepository('OjsAdminBundle:AdminJournalTheme')->find($previewThemeId);
            }
            $this->throw404IfNotFound($previewTheme);
            $journal->setTheme($previewTheme);
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('journal_view');

        $data['token'] = $token;
        $data['page'] = 'journal';
        $data['journal'] = $journal;
        $journal->setPublicURI($journalService->generateUrl($journal));
        $data['design'] = $journal->getDesign();
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['years'] = $this->setupIssuesURIsByYear(array_slice($issueRepo->getByYear($journal), 0, 5, true));
        $data['last_issue'] = $this->setupArticleURIs($issueRepo->findOneBy([
            'lastIssue' => true,
            'journal' => $journal,
        ]));
        $data['posts'] = $em->getRepository('OjsJournalBundle:JournalPost')->findBy(['journal' => $journal]);
        $data['journalPages'] = $em->getRepository('OjsJournalBundle:JournalPage')->findBy(['journal' => $journal]);

        $data['archive_uri'] = $this->generateUrl(
            'ojs_archive_index',
            [
                'slug' => $journal->getSlug(),
                'publisher' => $journal->getPublisher()->getSlug()
            ],
            true
        );

        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }

    /**
     * @param $years
     * @return mixed
     */
    private function setupIssuesURIsByYear($years)
    {
        foreach ($years as $year) {
            /** @var Issue $issue */
            foreach ($year as $issue) {
                $issue->setPublicURI(
                    $this->generateUrl(
                        'ojs_issue_page',
                        [
                            'publisher' => $issue->getJournal()->getPublisher()->getSlug(),
                            'journal_slug' => $issue->getJournal()->getSlug(),
                            'id' => $issue->getId(),
                        ],
                        true
                    )
                );
            }
        }

        return $years;
    }

    /**
     * @param Issue $last_issue
     * @return Issue|null
     */
    private function setupArticleURIs(Issue $last_issue = null)
    {
        if ($last_issue) {
            foreach ($last_issue->getArticles() as $article) {
                $article->setPublicURI(
                    $this->generateUrl(
                        'ojs_article_page',
                        [
                            'publisher' => $article->getIssue()->getJournal()->getPublisher()->getSlug(),
                            'slug' => $article->getIssue()->getJournal()->getSlug(),
                            'issue_id' => $article->getIssue()->getId(),
                            'article_id' => $article->getId(),
                        ],
                        true
                    )
                );
            }
            return $last_issue;
        }
        return null;
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

    public function journalBoardAction($slug)
    {
        /**
         * @var Journal $journal
         * @var EntityManager $em
         * @var BlockRepository $blockRepo
         */
        $em = $this->getDoctrine()->getManager();
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');

        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $boards = $journal->getBoards();

        $this->throw404IfNotFound($journal);
        $boardMembers = [];

        foreach ($boards as $board) {
            $boardMembers[$board->getId()] = $em
                ->getRepository(BoardMember::class)
                ->findBy(['board' => $board], ['seq' => 'ASC']);
        }

        $data = [
            'journal'       => $journal,
            'page'          => 'journal',
            'board'         => $boards,
            'board_members' => $boardMembers,
            'blocks'        => $blockRepo->journalBlocks($journal),
        ];

        return $this->render('OjsSiteBundle::Journal/journal_board.html.twig', $data);
    }

    /**
     * Also means last issue's articles
     *
     * @param $slug
     * @return Response
     */
    public function lastArticlesIndexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['articles'] = $em->getRepository('OjsJournalBundle:Article')->findBy(
            array('journal' => $journal)
        );
        $data['page'] = 'articles';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/last_articles_index.html.twig', $data);
    }

    public function archiveIndexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        /** @var Issue[] $issues */
        $issues = $em->getRepository('OjsJournalBundle:Issue')->findBy([
                'journal' => $journal
            ]
        );
        $groupedIssues = [];
        foreach ($issues as $issue) {
            $groupedIssues[$issue->getYear()][] = $issue;
        }
        krsort($groupedIssues);
        $data['groupedIssues'] = $groupedIssues;
        $data['page'] = 'archive';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/archive_index.html.twig', $data);
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
            ['journal' => $journal]
        );

        $data['page'] = 'announcement';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/announcement_index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function subscribeAction(Request $request, $slug)
    {
        $referer = $request->headers->get('referer');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(array('slug' => $slug));
        $this->throw404IfNotFound($journal);
        $email = $request->get('mail');

        $emailConstraint = new EmailConstraint();
        $errors = $this->get('validator')->validateValue(
            $email,
            $emailConstraint
        );
        if (count($errors) > 0 || empty($email)) {
            $this->errorFlashBag('invalid.mail');

            return $this->redirect($referer);
        }

        $subscribeMail = new SubscribeMailList();
        $subscribeMail->setMail($email);
        $subscribeMail->setJournal($journal);
        $em->persist($subscribeMail);
        $em->flush();

        $this->successFlashBag('successfully.subscribed');

        return $this->redirect($referer);
    }

    public function issuePageAction($id)
    {
        /**
         * @var BlockRepository $blockRepo
         * @var IssueRepository $issueRepo
         * @var Issue $issue
         */
        $em = $this->getDoctrine()->getManager();

        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
        $issue = $issueRepo->find($id);
        $this->throw404IfNotFound($issue);

        $blockRepo = $em->getRepository('OjsJournalBundle:Block');

        $blocks = $blockRepo->journalBlocks($issue->getJournal());

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('issue_view');

        $sections = $this->setupIssueSections($issue);

        return $this->render(
            'OjsSiteBundle:Issue:detail.html.twig',
            [
                'issue'     => $issue,
                'blocks'    => $blocks,
                'token'     => $token,
                'sections'  => $sections
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

    public function journalContactsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['contacts'] = $em->getRepository("OjsJournalBundle:JournalContact")->findBy(
            array('journal' => $journal)
        );
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        $data['blocks'] = $blockRepo->journalBlocks($journal);

        $data['journal'] = $journal;

        return $this->render("OjsSiteBundle:JournalContact:index.html.twig", $data);
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

    public function pageDetailAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('OjsAdminBundle:AdminPage')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($page);

        return $this->render('OjsSiteBundle:Site:page.html.twig', ['adminPage' => $page]);
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

    public function postDetailAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('OjsAdminBundle:AdminPost')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($post);

        return $this->render('OjsSiteBundle:Site:post.html.twig', ['post' => $post]);
    }
}
