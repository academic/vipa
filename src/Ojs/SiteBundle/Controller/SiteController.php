<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Document\ObjectDownloads;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Helper\FileHelper;
use Ojs\JournalBundle\Entity\File;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\InstitutionRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\JournalBundle\Entity\Sums;
use Ojs\SiteBundle\Entity\BlockRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Parser;

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

        $journals = $em->getRepository('OjsJournalBundle:Journal')->getHomePageList();
        $data["journals"] = $journals;
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
                return '<a href="'.$this->generateUrl(
                    'ojs_journals_index',
                    ['filter' => ['subject' => $node['id']]]
                ).'">'
                .$node['subject'].' ('.$node['totalJournalCount'].')</a>';
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
        /** @var Sums[] $sums */
        $sums = $em->getRepository('OjsJournalBundle:Sums')->findAll();
        foreach ($sums as $sum) {
            $total = $sum->getSum();
            if ($sum->getEntity() === 'OjsJournalBundle:Journal') {
                $data['stats']['journal'] = $total;
                continue;
            }
            if ($sum->getEntity() === 'OjsJournalBundle:Article') {
                $data['stats']['article'] = $total;
                continue;
            }
            if ($sum->getEntity() === 'OjsJournalBundle:Subject') {
                $data['stats']['subject'] = $total;
                continue;
            }
            if ($sum->getEntity() === 'OjsJournalBundle:Institution') {
                $data['stats']['institution'] = $total;
                continue;
            }
            if ($sum->getEntity() === 'OjsUserBundle:User') {
                $data['stats']['user'] = $total;
                continue;
            }
        }

        // anything else is anonym main page
        return $this->render('OjsSiteBundle::Site/home.html.twig', $data);
    }

    public function staticPagesAction($page = 'static')
    {
        $data['page'] = $page;

        return $this->render('OjsSiteBundle:Site:static/'.$page.'.html.twig', $data);
    }

    public function institutionsIndexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var InstitutionRepository $repo */
        $repo = $em->getRepository('OjsJournalBundle:Institution');
        $data['entities'] = $repo->getAllWithDefaultTranslation();
        $data['page'] = 'institution';

        /*
         * @todo implement string from db
         * $data['design']
         */

        return $this->render('OjsSiteBundle::Institution/institutions_index.html.twig', $data);
    }

    public function institutionPageAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $data['entity'] = $em->getRepository('OjsJournalBundle:Institution')->findOneBy(['slug' => $slug]);
        $data['page'] = 'organizations';

        return $this->render('OjsSiteBundle::Institution/institution_index.html.twig', $data);
    }

    public function journalsIndexAction(Request $request, $page, $institution = null)
    {
        $searchManager = $this->get('ojs_search_manager');
        $searchManager->setPage($page);
        $filter = $request->get('filter', []);
        if ($institution) {
            /** @var Institution $institutionObj */
            $institutionObj = $this->getDoctrine()->getManager()->getRepository(
                'OjsJournalBundle:Institution'
            )->findOneBy(['slug' => $institution]);
            $filter['institution'] = $institutionObj->getId();
            $data['institutionObject'] = $institutionObj;
        }
        if (!empty($filter)) {
            $searchManager->addFilters($filter);
        }
        $result = $searchManager->searchJournal()->getResult();
        $data['result'] = $result;
        $data['total_count'] = $searchManager->getCount();
        $data['page'] = 'journals';
        $data['current_page'] = $page;
        $data['page_count'] = $searchManager->getPageCount();
        $data['aggregations'] = $searchManager->getAggregations();
        $data['filter'] = $filter;

        return $this->render('OjsSiteBundle::Journal/journals_index.html.twig', $data);
    }

    public function journalIndexAction($slug)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var JournalRepository $journalRepo */
        $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $journalRepo->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['last_issue'] = $journalRepo->getLastIssueId($journal);
        $data['years'] = $journalRepo->getIssuesByYear($journal);
        $data['journal'] = $journal;
        $data['page'] = 'journal';
        $data['blocks'] = $blockRepo->journalBlocks($journal);

        /**
         * @todo implement string from db
         * $data['design']
         */

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

    public function announcementIndexAction($slug)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('OjsSiteBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $service = $this->get('okulbilisimcmsbundle.twig.post_extension');
        $data['announcements'] = $em->getRepository('OkulbilisimCmsBundle:Post')
            ->getByType('announcement', $service->cmsobject($journal), $journal->getId());

        $data['page'] = 'announcement';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/announcement_index.html.twig', $data);
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

    public function downloadFileAction(Request $request, $id)
    {
        /** @var File $file */
        $file = $this->getDoctrine()->getManager()->find('OjsJournalBundle:File', $id);
        if (!$file) {
            throw new NotFoundHttpException();
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        $objectDownload = new ObjectDownloads();

        $objectDownload->setEntity('file');
        $objectDownload->setFilePath($file->getPath());
        $objectDownload->setIpAddress($request->getClientIp());
        $objectDownload->setLogDate(new \DateTime("now"));
        $objectDownload->setObjectId($id);
        $objectDownload->setTransferSize($file->getSize());
        $dm->persist($objectDownload);
        $dm->flush();

        $fileHelper = new FileHelper();

        $file = $fileHelper->generatePath($file->getName(), false).$file->getName();

        $uploaddir = $this->get('kernel')->getRootDir().'/../web/uploads/';

        $yamlParser = new Parser();
        $vars = $yamlParser->parse(
            file_get_contents(
                $this->container->getParameter('kernel.root_dir').
                '/config/media.yml'
            )
        );
        $mappings = $vars['oneup_uploader']['mappings'];
        $url = false;
        foreach ($mappings as $key => $value) {
            if (is_file($uploaddir.$key.'/'.$file)) {
                $url = '/uploads/'.$key.'/'.$file;
                break;
            }
        }
        if (!$url) {
            throw new NotFoundHttpException("File not found on drive");
        }

        return RedirectResponse::create($url);
    }

    public function journalPageDetailAction($slug, $journal_slug)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        if (!$journal) {
            throw new NotFoundHttpException("Journal not found!");
        }
        $twig = $this->get('okulbilisimcmsbundle.twig.post_extension');
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
