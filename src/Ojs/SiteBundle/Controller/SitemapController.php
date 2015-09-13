<?php
namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\SitemapIndex;

class SitemapController extends Controller
{
    public function indexAction(Request $request)
    {
        $router = $this->get('router');
        $siteMap = new SitemapIndex();
        $maps = [
            //'ojs_journals_sitemap',
            'ojs_publishers_sitemap',
            'ojs_static_sitemap',
        ];
        foreach ($maps as $map) {
            $siteMap
                ->add(
                    $request->getSchemeAndHttpHost()
                    . $router->generate($map, ['_format' => 'xml']),
                    (new \DateTime())->format('Y-m-d')
                );
        }

        return $this->response($siteMap);
    }

    private function response($content)
    {
        $response = new Response();
        $response->headers->add(['content-type' => 'text/xml']);
        $response->setContent($content);

        return $response;
    }

    public function subjectAction($subject, $publisher = 'www')
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Subject $subject */
        $subject = $em->getRepository('OjsJournalBundle:Subject')->findOneBy(['slug' => $subject]);

        if ($publisher == 'www' || empty($publisher)) {
            $journals = $subject->getJournals();
            $publisher = null;
        } else {
            /** @var JournalRepository $journalRepo */
            $journalRepo = $em->getRepository('OjsJournalBundle:Journal');
            $journals = $journalRepo->getByPublisherAndSubject(
                $publisher,
                $subject
            );
        }
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $siteMap->add(
            $router->generate(
                'ojs_journal_index',
                [
                    'subject' => $subject->getSlug(),
                    'publisher' => $publisher,
                ],
                RouterInterface::ABSOLUTE_URL
            )
        );

        foreach ($journals as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $router->generate(
                    'ojs_journal_index',
                    [
                        'slug' => $journal->getSlug(),
                        'publisher' => $journal->getPublisher()->getSlug(),
                    ],
                    RouterInterface::ABSOLUTE_URL
                ),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }

    public function subjectsAction(Request $request, $publisher = 'www', $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        $em = $this->getDoctrine()->getManager();
        /** @var SubjectRepository $subjectRepo */
        $subjectRepo = $em->getRepository('OjsJournalBundle:Subject');
        if ($publisher == 'www' || empty($publisher)) {
            $subjects = $subjectRepo->findAll();
        } else {
            $subjects = $subjectRepo->getByPublisher($publisher);
        }

        foreach ($subjects as $subject) {
            $siteMapIndex->add(
                $request->getSchemeAndHttpHost() .
                $router->generate(
                    'ojs_subject_sitemap',
                    ['subject' => $subject->getSlug(), 'publisher' => $publisher, '_format' => 'xml']
                ),
                $subject->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);
    }

    public function journalAction(Request $request, $journal, $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository("OjsJournalBundle:Journal")->findOneBy(['slug' => $journal]);

        if (!$journal) {
            throw new \Exception("Journal not found!");
        }
        $router = $this->get('router');
        $maps = [
            'ojs_journal_detail_sitemap',
            'ojs_articles_sitemap',
            'ojs_issues_sitemap',
            'ojs_last_issue_sitemap',
        ];
        foreach ($maps as $map) {
            $siteMapIndex->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    $map,
                    [
                        'journal' => $journal->getSlug(),
                        'publisher' => $journal->getPublisher()->getSlug(),
                        '_format' => $_format,
                    ]
                ),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);
    }

    public function journalDetailAction(Request $request, $journal)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository("OjsJournalBundle:Journal")->findOneBy(['slug' => $journal]);

        $siteMap = new Sitemap();
        $router = $this->get('router');
        $maps = [
            'ojs_journal_index',
            'ojs_journal_index_articles',
            'ojs_last_articles_index',
            'ojs_archive_index',

        ];
        foreach ($maps as $map) {
            $siteMap->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    $map,
                    ['slug' => $journal->getSlug(), 'publisher' => $journal->getPublisher()->getSlug()]
                ),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }

    public function lastIssueAction(Request $request, $journal)
    {

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal]);

        //@todo thats wrong
        $siteMap = new Sitemap();
        $router = $this->get('router');
        /** @var Issue $issue $lastIssue */
        $issue = $journal->getIssues()->last();
        foreach ($issue->getArticles() as $article) {
            /** @var Article $article */
            $siteMap->add(
                $request->getSchemeAndHttpHost() .
                $router->generate(
                    'ojs_article_page',
                    [
                        'slug' => $article->getJournal()->getSlug(),
                        'article_id' => $article->getId(),
                        'publisher' => $journal->getPublisher()->getSlug(),
                    ]
                ),
                $article->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }

    public function issuesAction($journal)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal]);
        $siteMap = new  Sitemap();

        /*
        foreach ($journal->getIssues() as $issue) {
            //@todo
        }
        */

        return $this->response($siteMap);
    }

    public function journalsAction(Request $request, $publisher)
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $em = $this->getDoctrine()->getManager();


        /** @var Publisher $journals */
        $publisher = $em->getRepository('OjsJournalBundle:Publisher')->find($publisher);

        /** @var Journal[] $journals */
        $journals = $em->getRepository('OjsJournalBundle:Journal')->findBy(['publisher' => $publisher]);

        foreach ($journals as $journal) {
            $siteMap->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    'ojs_journal_sitemap',
                    [
                        'journal' => $journal->getId(),
                        '_format' => 'xml',
                    ]
                ),
                $journal->getUpdated()->format('Y-d-m')
            );
        }

        return $this->response($siteMap);
    }

    public function publisherAction(Request $request, $publisher)
    {
        $siteMap = new SitemapIndex();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Publisher $publisher */
        $publisher = $em->getRepository('OjsJournalBundle:Publisher')->findOneBy(['slug' => $publisher]);
        $journals = $publisher->getJournals();
        $siteMap->add(
            $request->getSchemeAndHttpHost()
            .
            $router->generate(
                'ojs_subjects_sitemap',
                [
                    'publisher' => $publisher->getSlug(),
                    '_format' => 'xml',
                ]
            ),
            $publisher->getUpdated()->format('Y-m-d')
        );
        foreach ($journals as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    'ojs_journal_sitemap',
                    [
                        'journal' => $journal->getSlug(),
                        'publisher' => $journal->getPublisher()->getSlug(),
                        '_format' => 'xml',
                    ]
                ),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }

    public function publishersAction(Request $request)
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $publishers = $em->getRepository('OjsJournalBundle:Publisher')->findAll();
        foreach ($publishers as $publisher) {
            /** @var Publisher $publisher */
            $siteMapIndex->add(
                $request->getScheme() . ':' . $router->generate(
                    'ojs_publisher_sitemap',
                    [
                        'publisher' => $publisher->getSlug(),
                        '_format' => 'xml',
                    ]
                ),
                $publisher->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);
    }

    public function articleAction(Request $request, $journal)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal]);
        $siteMap = new Sitemap();
        $router = $this->get('router');

        $articles = $journal->getArticles();

        foreach ($articles as $article) {
            /** @var Article $article */
            $siteMap
                ->add(
                    $request->getSchemeAndHttpHost() . $router->generate(
                        'ojs_article_page',
                        [
                            'slug' => $journal->getSlug(),
                            'article_id' => $article->getId(),
                            'publisher' => $journal->getPublisher()->getSlug(),
                        ]
                    ),
                    $article->getUpdated()->format('Y-m-d')
                );
        }

        return $this->response($siteMap);
    }

    public function staticAction(Request $request)
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $maps = [
            'ojs_public_index',
            'ojs_browse_index',
            'ojs_publishers_index',
            'ojs_journals_index',
            'tos',
            'privacy',
        ];
        foreach ($maps as $map) {
            $siteMap->add(
                $request->getSchemeAndHttpHost() .
                $router->generate($map),
                (new \DateTime())->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }
}
