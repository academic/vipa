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
use Ojs\WikiBundle\Entity\Page;
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
            'ojs_journals_sitemap',
            'ojs_institutions_sitemap',
            'ojs_subjects_sitemap',
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

    public function subjectAction(Request $request, Subject $subject, $_format = 'xml')
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $siteMap->add(
          $request->getSchemeAndHttpHost().
          $router->generate('ojs_journals_index',['subject'=>$subject->getSlug()])
        );

        foreach ($subject->getJournals() as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $request->getSchemeAndHttpHost().
                $router->generate('ojs_journal_index',['journal_id'=>$journal->getId()]),
                $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMap);
    }

    public function subjectsAction(Request $request, $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $subjects = $em->getRepository('OjsJournalBundle:Subject')->findAll();
        foreach ($subjects as $subject) {
            /** @var Subject $subject */
            $siteMapIndex->add(
                $request->getSchemeAndHttpHost() .
                $router->generate('ojs_subject_sitemap', ['subject' => $subject->getId(), '_format' => 'xml'])
                , $subject->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);
    }

    public function journalAction(Request $request, Journal $journal, $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        $maps = [
            'ojs_journal_detail_sitemap',
            'ojs_articles_sitemap',
            'ojs_issues_sitemap',
            'ojs_last_issue_sitemap',
            'ojs_wiki_sitemap'
        ];
        foreach ($maps as $map) {
            $siteMapIndex->add(
                $request->getSchemeAndHttpHost()
                . $router->generate($map, ['journal' => $journal->getId(), '_format' => $_format])
                , $journal->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);

    }

    public function wikiAction(Request $request, Journal $journal, $_format = 'xml')
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $wikis = $journal->getPages();
        foreach ($wikis as $wiki) {
            /** @var Page $wiki */
            $siteMap->add(
                $request->getSchemeAndHttpHost().
                $router->generate('ojs_wiki_page_detail',['slug'=>$wiki->getSlug()]),
                $wiki->getUpdated()->format('Y-m-d')
            );
        }
        return $this->response($siteMap);

    }
    public function journalDetailAction(Request $request, Journal $journal, $_format = 'xml')
    {
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
                . $router->generate($map, ['journal_id' => $journal->getId()])
                , $journal->getUpdated()->format('Y-m-d')
            );
        }
        return $this->response($siteMap);

    }

    public function lastIssueAction(Request $request, Journal $journal, $_format = 'xml')
    {
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
                    'journal_id' => $article->getJournal()->getId(),
                    'article_id' => $article->getId()
                ]),
                $article->getUpdated()->format('Y-d-m')
            );
        }
        return $this->response($siteMap);


    }

    public function issuesAction(Request $request, Journal $journal, $_format = 'xml')
    {
        $siteMap = new  Sitemap();
        $router = $this->get('router');

        foreach ($journal->getIssues() as $issue) {
            //@todo
        }
    }

    public function journalsAction(Request $request)
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $journals = $em->getRepository('OjsJournalBundle:Journal')->findAll();


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

    public function institutionAction(Request $request, Institution $institution, $_format = 'xml')
    {
        $siteMap = new Sitemap();
        $router = $this->get('router');
        $journals = $institution->getJournals();
        $siteMap->add(
            $request->getSchemeAndHttpHost() .
            $router->generate('ojs_institution_page', ['institution_id' => $institution->getId()]),
            $institution->getUpdated()->format('Y-m-d')
        );
        foreach ($journals as $journal) {
            /** @var Journal $journal */
            $siteMap->add(
                $request->getSchemeAndHttpHost()
                . $router->generate(
                    'ojs_journal_index',
                    [
                        'journal_id' => $journal->getId()
                    ]
                ),
                $journal->getUpdated()->format('Y-d-m')
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
                $request->getSchemeAndHttpHost() . $router->generate(
                    'ojs_institution_sitemap',
                    [
                        'institution' => $institution->getId(),
                        '_format' => 'xml'
                    ]
                ),
                $institution->getUpdated()->format('Y-m-d')
            );
        }

        return $this->response($siteMapIndex);
    }

    public function articleAction(Request $request, Journal $journal, $_format = 'xml')
    {

        $siteMap = new Sitemap();
        $router = $this->get('router');

        $articles = $journal->getArticles();

        foreach ($articles as $article) {
            /** @var Article $article */
            $siteMap
                ->add($request->getSchemeAndHttpHost() . $router->generate('ojs_article_page', [
                        'article_id' => $article->getId(),
                        'journal_id' => $journal->getId(),
                    ]), $article->getUpdated()->format('Y-m-d'));
        }
        return $this->response($siteMap);
    }

    public function staticAction(Request $request,$_format='xml')
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
                $request->getSchemeAndHttpHost().
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