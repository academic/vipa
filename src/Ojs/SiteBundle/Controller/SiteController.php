<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller {

    /**
     * Global index page
     * @return Response
     */
    public function indexAction()
    {
        $data['page'] = 'index';
        $em = $this->getDoctrine()->getManager();
        $data["journals"] = $em->getRepository('OjsJournalBundle:Journal')->findBy(array('status' => 3), array(), 12);
        $data['institutions'] = $em->getRepository('OjsJournalBundle:Institution')->findBy(array(), array(), 6);
        $repo = $this->getDoctrine()->getRepository('OjsJournalBundle:Subject');
        $options = [
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'idField' => true,
            'nodeDecorator' => function ($node) {
                return '<a href="' . $this->generateUrl('ojs_journals_index', ['filter' => ['subject' => $node['id']]]) . '">'
                        . $node['subject'] . ' (' . $node['totalJournalCount'] . ')</a>';
            }];
                $data['subjects'] = $repo->childrenHierarchy(null, false, $options);
                $data['page'] = 'index';
                $sumRepo = $em->getRepository('OjsJournalBundle:Sums');
                $data['stats'] = [
                    'journal' => $sumRepo->findOneBy(['entity' => 'OjsJournalBundle:Journal'])->getSum(),
                    'article' => $sumRepo->findOneBy(['entity' => 'OjsJournalBundle:Article'])->getSum(),
                    'subject' => $sumRepo->findOneBy(['entity' => 'OjsJournalBundle:Subject'])->getSum(),
                    'institution' => $sumRepo->findOneBy(['entity' => 'OjsJournalBundle:Institution'])->getSum(),
                    'user' => $sumRepo->findOneBy(['entity' => 'OjsUserBundle:User'])->getSum(),
                ];
                // anything else is anonym main page
                return $this->render('OjsSiteBundle::Site/home.html.twig', $data);
            }

            public function browseIndexAction()
            {
                $data['page'] = 'browse';
                return $this->render('OjsSiteBundle::Site/browse_index.html.twig', $data);
            }

            public function categoriesIndexAction()
            {
                $data['page'] = 'categories';
                return $this->render('OjsSiteBundle::Site/categories_index.html.twig', $data);
            }

            public function topicsIndexAction()
            {
                $data['page'] = 'topics';
                return $this->render('OjsSiteBundle::Site/topics_index.html.twig', $data);
            }

            public function profileIndexAction()
            {
                $data['page'] = 'profile';
                return $this->render('OjsSiteBundle::Site/profile_index.html.twig', $data);
            }

            public function staticPagesAction($page = 'static')
            {
                $data['page'] = $page;
                return $this->render('OjsSiteBundle:Site:static/' . $page . '.html.twig', $data);
            }

            public function institutionsIndexAction()
            {
                $em = $this->getDoctrine()->getManager();
                $data['entities'] = $em->getRepository('OjsJournalBundle:Institution')->findAll();
                $data['page'] = 'institution';
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
                    $institutionObj = $this->getDoctrine()->getManager()->getRepository('OjsJournalBundle:Institution')->findOneBy(['slug' => $institution]);
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

            public function journalIndexAction(Request $request, $slug)
            {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                /** @var JournalRepository $journalRepo */
                $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
                /** @var Journal $journal */
                $journal = $journalRepo->findOneBy(['slug' => $slug]);
                $this->throw404IfNotFound($journal);
                $data['last_issue'] = $journalRepo->getLastIssueId($journal);
                $volumes = $journalRepo->getVolumes($journal);
                $data['volumes'] = $volumes;
                $data['journal'] = $journal;
                $data['users'] = $em->getRepository('OjsUserBundle:UserJournalRole')->getUsers($journal->getId(), true);
                $data['page'] = 'journal';
                $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($journal);
                return $this->render('OjsSiteBundle::Journal/journal_index.html.twig', $data);
            }

            public function journalArticlesAction($slug)
            {
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                /** @var Journal $journal */
                $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
                $articles = $journal->getArticles();
                $data = [
                    'journal' => $journal,
                    'articles' => $articles,
                    'page' => 'journal',
                    'blocks' => $em->getRepository('OjsSiteBundle:Block')->journalBlocks($journal),
                ];
                return $this->render('OjsSiteBundle::Journal/journal_articles.html.twig', $data);
            }

            /**
             * Also means last issue's articles
             * @param integer $journal_id
             */
            public function lastArticlesIndexAction($slug)
            {
                $em = $this->getDoctrine()->getManager();
                $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
                $this->throw404IfNotFound($data['journal']);
                $data['articles'] = $em->getRepository('OjsJournalBundle:Article')->findByJournalId($data['journal']->getId());
                $data['page'] = 'articles';
                $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);

                return $this->render('OjsSiteBundle::Journal/last_articles_index.html.twig', $data);
            }

            public function archiveIndexAction($slug)
            {
                $em = $this->getDoctrine()->getManager();
                $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
                $this->throw404IfNotFound($data['journal']);
                // get all issues
                $data['issues'] = $em->getRepository('OjsJournalBundle:Issue')->findBy(array('journalId' => $data['journal']->getId()));
                $data['issues_grouped'] = [];
                foreach ($data['issues'] as $issue) {
                    $data['issues_grouped'][$issue->getYear()][] = $issue;
                }
                $data['page'] = 'archive';
                $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);

                return $this->render('OjsSiteBundle::Journal/archive_index.html.twig', $data);
            }

            public function announcementIndexAction($slug)
            {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
                $this->throw404IfNotFound($data['journal']);
                $service = $this->get('okulbilisimcmsbundle.twig.post_extension');
                $data['announcements'] = $em->getRepository('OkulbilisimCmsBundle:Post')
                        ->getByType('announcement', $service->cmsobject($data['journal']), $data['journal']->getId());

                $data['page'] = 'announcement';
                $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);

                return $this->render('OjsSiteBundle::Journal/announcement_index.html.twig', $data);
            }

            public function issuePageAction($id)
            {
                $data = [];
                $em = $this->getDoctrine()->getManager();
                $issueRepo = $em->getRepository('OjsJournalBundle:Issue');
                /** @var Issue $issue */
                $issue = $issueRepo->find($id)
                ;
                $data['issue'] = $issue;
                $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($issue->getJournal());

                return $this->render('OjsSiteBundle:Issue:detail.html.twig', $data);
            }

            public function journalContactsAction($slug)
            {
                $em = $this->getDoctrine()->getManager();
                $data['journal'] = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
                $this->throw404IfNotFound($data['journal']);
                $data['contacts'] = $em->getRepository("OjsJournalBundle:JournalContact")->findBy(['journalId' => $data['journal']->getId()]);
                $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);

                return $this->render("OjsSiteBundle:JournalContact:index.html.twig", $data);
            }

        }
        