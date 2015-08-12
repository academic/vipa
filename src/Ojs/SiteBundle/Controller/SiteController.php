<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Elastica\Query\MatchAll;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\InstitutionRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\JournalBundle\Entity\SubscribeMailList;
use Ojs\SiteBundle\Entity\BlockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $data['journals'] = $em->getRepository('OjsJournalBundle:Journal')->getHomePageList();

        /** @var SubjectRepository $repo */
        $repo = $em->getRepository('OjsJournalBundle:Subject');
        $options = [
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'idField' => true,
            'nodeDecorator' => function ($node) {
                return '<a href="' . $this->generateUrl('ojs_site_explore_index',
                    ['filter' => ['subject' => $node['id']]]) . '">' . $node['subject'] .
                    ' (' . $node['totalJournalCount'] . ')</a>';
            },
        ];

        $data['subjects'] = $repo->childrenHierarchy(null, false, $options);
        $data['page'] = 'index';

        $data['stats'] = [
            'journal' => 0,
            'article' => 0,
            'subject' => 0,
            'institution' => 0,
            'user' => 0,
        ];

        $data['stats']['journal'] = $this->get('fos_elastica.index.search.journal')->count(new MatchAll());
        $data['stats']['article'] = $this->get('fos_elastica.index.search.articles')->count(new MatchAll());
        $data['stats']['subject'] = $this->get('fos_elastica.index.search.subject')->count(new MatchAll());
        $data['stats']['institution'] = $this->get('fos_elastica.index.search.institution')->count(new MatchAll());
        $data['stats']['user'] = $this->get('fos_elastica.index.search.user')->count(new MatchAll());

        $data['announcements'] = $em->getRepository('OjsAdminBundle:AdminAnnouncement')->findAll();
        $data['announcement_count'] = count($data['announcements']);

        // anything else is anonym main page
        return $this->render('OjsSiteBundle::Site/home.html.twig', $data);
    }

    public function staticPagesAction($page = 'static')
    {
        $data['page'] = $page;
        return $this->render('OjsSiteBundle:Site:static/' . $page . '.html.twig', $data);
    }

    public function institutionsIndexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var InstitutionRepository $repo */
        $repo = $em->getRepository('OjsJournalBundle:Institution');
        $data['entities'] = $repo->getAllWithDefaultTranslation();
        $data['page'] = 'institution';

        return $this->render('OjsSiteBundle::Institution/institutions_index.html.twig', $data);
    }

    public function institutionPageAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $data['entity'] = $em->getRepository('OjsJournalBundle:Institution')->findOneBy(['slug' => $slug]);
        $data['page'] = 'organizations';

        /*
         * @todo implement string from db
         * $data['design']
         */
        return $this->render('OjsSiteBundle::Institution/institution_index.html.twig', $data);
    }


    public function journalIndexAction($institution, $slug)
    {
        /**
         * @var EntityManager $em
         * @var JournalRepository $journalRepo
         * @var BlockRepository $blockRepo
         * @var Journal $journal
         */

        $em = $this->getDoctrine()->getManager();
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');

        $institutionEntity = $em->getRepository('OjsJournalBundle:Institution')->findOneBy(['slug' => $institution]);
        $this->throw404IfNotFound($institutionEntity);

        $journal = $journalRepo->findOneBy(['slug' => $slug, 'institution' => $institutionEntity]);
        $this->throw404IfNotFound($journal);

        $data['last_issue'] = $journalRepo->getLastIssueId($journal);
        $data['years'] = $journalRepo->getIssuesByYear($journal);
        $data['journal'] = $journal;
        $data['page'] = 'journal';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['design'] = $journal->getDesign();

        return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
    }

    public function journalArticlesAction($slug)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
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
     * Also means last issue's articles
     *
     * @param $slug
     * @return Response
     */
    public function lastArticlesIndexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['articles'] = $em->getRepository('OjsJournalBundle:Article')->findBy(
            array('journalId' => $journal->getId())
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
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        /** @var Issue[] $issues */
        $issues = $em->getRepository('OjsJournalBundle:Issue')->findBy(
            array('journalId' => $journal->getId())
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
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $service = $this->get('ojs.cms.twig.post_extension');
        $data['announcements'] = $em->getRepository('OkulbilisimCmsBundle:Post')
            ->getByType('announcement', $service->cmsobject($journal), $journal->getId());

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
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Issue $issue */
        $issue = $issueRepo->find($id);
        $data['issue'] = $issue;
        $data['blocks'] = $blockRepo->journalBlocks($issue->getJournal());

        return $this->render('OjsSiteBundle:Issue:detail.html.twig', $data);
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
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        $data['blocks'] = $blockRepo->journalBlocks($journal);

        $data['journal'] = $journal;

        return $this->render("OjsSiteBundle:JournalContact:index.html.twig", $data);
    }

    public function journalPageDetailAction($slug, $journal_slug)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        if (!$journal) {
            throw new NotFoundHttpException("Journal not found!");
        }
        $twig = $this->get('ojs.cms.twig.post_extension');
        $journalKey = $twig->encode($journal);

        $page = $em->getRepository('OkulbilisimCmsBundle:Post')->findOneBy(
            [
                'slug' => $slug,
                'object' => $journalKey,
                'objectId' => $journal->getId(),
            ]
        );
        if (!$page) {
            throw new NotFoundHttpException("Page not found!");
        }
        $data['journal'] = $journal;
        $data['content'] = $page;

        return $this->render('OjsSiteBundle:Page:detail.html.twig', $data);
    }
}
