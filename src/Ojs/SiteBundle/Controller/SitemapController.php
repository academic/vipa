<?php
/**
 * Date: 8.12.14
 * Time: 10:50
 */

namespace Ojs\SiteBundle\Controller;


use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            'ojs_institutions_sitemap',
            'ojs_static_sitemap'
        ];
        foreach ($maps as $map) {
            $siteMap
                ->add(
                    $request->getSchemeAndHttpHost()
                    . $router->generate($map, ['_format' => 'xml']),
                    (new \DateTime())->format('Y-m-d'));
        }

        return $this->response($siteMap);
    }

    public function subjectAction(Request $request, $subject, $institution = 'www', $_format = 'xml')
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $subject = $em->getRepository('OjsJournalBundle:Subject')->findOneBy(['slug'=>$subject]);

        if ($institution == 'www' || empty($institution)) {
            $journals = $subject->getJournals();
            $institution=null;

        } else {
            /** @var Subject $subject */
            $journals = $em->getRepository('OjsJournalBundle:Journal')->getByInstitutionAndSubject($institution,$subject);
        }
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $siteMap->add(
            $request->getScheme().':' .
            $router->generate('ojs_journals_index',
                [
                    'subject' => $subject->getSlug(),
                    'institution' => $institution
                ])
        );

        foreach ($journals as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $request->getScheme() .':'.
                $router->generate('ojs_journal_index', ['slug' => $journal->getSlug(),
                    'institution' => $journal->getInstitution()->getSlug()]),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }

    public function subjectsAction(Request $request, $institution = 'www', $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if ($institution == 'www' || empty($institution)) {
            $subjects = $em->getRepository('OjsJournalBundle:Subject')->findAll();
        } else {
            /** @var Subject $subject */
            $subjects = $em->getRepository('OjsJournalBundle:Subject')->getByInstitution($institution);
        }
        foreach ($subjects as $subject) {
            /** @var Subject $subject */
            $siteMapIndex->add(
                $request->getSchemeAndHttpHost() .
                $router->generate('ojs_subject_sitemap', ['subject' => $subject->getSlug(), 'institution' => $institution, '_format' => 'xml'])
                , $subject->getUpdated()->format('Y-m-d')
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

        if (!$journal)
            throw new \Exception("Journal not found!");
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
                . $router->generate($map, [
                        'journal' => $journal->getSlug(),
                        'institution' => $journal->getInstitution()->getSlug(),
                        '_format' => $_format]
                )
                , $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);

    }


    public function journalDetailAction(Request $request, $journal, $_format = 'xml')
    {
        /** @var \Doctrine\ORM\EntityManager $em */
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
                . $router->generate($map, ['slug' => $journal->getSlug(), 'institution' => $journal->getInstitution()->getSlug()])
                , $journal->getUpdated()->format('Y-m-d')
            );
        }
        return $this->response($siteMap);

    }

    public function lastIssueAction(Request $request, $journal, $_format = 'xml')
    {

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug'=>$journal]);

        //@todo thats wrong
        $siteMap = new Sitemap();
        $router = $this->get('router');
        /** @var Issue $issue $lastIssue */
        $issue = $journal->getIssues()->last();
        foreach ($issue->getArticles() as $article) {
            /** @var Article $article */
            $siteMap->add(
                $request->getSchemeAndHttpHost() .
                $router->generate('ojs_article_page', [
                    'slug' => $article->getJournal()->getSlug(),
                    'article_slug' => $article->getSlug(),
                    'institution' => $journal->getInstitution()->getSlug()
                ]),
                $article->getUpdated()->format('Y-m-d')
            );
        }
        return $this->response($siteMap);


    }

    public function issuesAction(Request $request, $journal, $_format = 'xml')
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug'=>$journal]);

        $siteMap = new  Sitemap();
        $router = $this->get('router');

        foreach ($journal->getIssues() as $issue) {
            //@todo
        }
        return $this->response($siteMap);
    }

    public function journalsAction(Request $request, $institution)
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $journals = $em->getRepository('OjsJournalBundle:Journal')->findBy(['institutionId' => $institution]);


        foreach ($journals as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    'ojs_journal_sitemap',
                    [
                        'journal' => $journal->getId(),
                        '_format' => 'xml'
                    ]
                ),
                $journal->getUpdated()->format('Y-d-m')
            );
        }

        return $this->response($siteMap);

    }

    public function institutionAction(Request $request, $institution, $_format = 'xml')
    {
        $siteMap = new SitemapIndex();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Institution $institution */
        $institution = $em->getRepository('OjsJournalBundle:Institution')->findOneBy(['slug' => $institution]);
        $journals = $institution->getJournals();
        $siteMap->add($request->getSchemeAndHttpHost()
            .
            $router->generate(
                'ojs_subjects_sitemap',
                [
                    'institution' => $institution->getSlug(),
                    '_format' => 'xml'
                ]
            ),
            $institution->getUpdated()->format('Y-m-d')
        );
        foreach ($journals as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    'ojs_journal_sitemap',
                    [
                        'journal' => $journal->getSlug(),
                        'institution' => $journal->getInstitution()->getSlug(),
                        '_format' => 'xml'
                    ]
                ),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);

    }

    public function institutionsAction(Request $request, $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $institutions = $em->getRepository('OjsJournalBundle:Institution')->findAll();
        foreach ($institutions as $institution) {
            /** @var Institution $institution */
            $siteMapIndex->add(
                $request->getScheme() . ':' . $router->generate(
                    'ojs_institution_sitemap',
                    [
                        'institution' => $institution->getSlug(),

                        '_format' => 'xml'
                    ]
                ),
                $institution->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);
    }

    public function articleAction(Request $request, $journal, $_format = 'xml')
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug'=>$journal]);
        $siteMap = new Sitemap();
        $router = $this->get('router');

        $articles = $journal->getArticles();

        foreach ($articles as $article) {
            /** @var Article $article */
            $siteMap
                ->add($request->getSchemeAndHttpHost() . $router->generate('ojs_article_page', [
                        'slug' => $journal->getSlug(),
                        'article_slug' => $article->getSlug(),
                        'institution'=>$journal->getInstitution()->getSlug()
                    ]), $article->getUpdated()->format('Y-m-d'));
        }
        return $this->response($siteMap);
    }

    public function staticAction(Request $request, $_format = 'xml')
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $maps = [
            'ojs_public_index',
            'ojs_browse_index',
            'ojs_institutions_index',
            'ojs_categories_index',
            'ojs_topic_index',
            'ojs_profile_index',
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

    private function response($content)
    {
        $response = new Response();
        $response->headers->add(['content-type' => 'text/xml']);
        $response->setContent($content);
        return $response;
    }
}