<?php
/**
 * Date: 8.12.14
 * Time: 10:50
 */

namespace Ojs\SiteBundle\Controller;


use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\SitemapIndex;

class RssController extends Controller
{
    public function indexAction(Request $request)
    {
        $router = $this->get('router');
        $siteMap = new SitemapIndex();
        $maps = [
            'ojs_journals_rss',
            'ojs_institutions_rss',
            'ojs_subjects_rss',
        ];
        foreach ($maps as $map) {
            $siteMap
                ->add(
                    $request->getSchemeAndHttpHost()
                    . $router->generate($map, ['_format' => 'xml']),
                    (new \DateTime())->format('Y-m-d'));
        }

        $response = new Response();
        $response->headers->add(['content-type' => 'text/xml']);

        $response->setContent($siteMap->toString());

        return $response;

    }

    public function subjectAction()
    {

    }

    public function journalAction(Request $request, Journal $journal, $_format = 'xml')
    {
        $siteMapIndex = new SitemapIndex();
        $router = $this->get('router');
        $maps = [
            'ojs_articles_rss',
            'ojs_issues_rss',
            'ojs_last_issue_rss'
        ];
        foreach ($maps as $map) {
            $siteMapIndex->add(
                $request->getSchemeAndHttpHost()
                . $router->generate($map, ['journal' => $journal->getId(), '_format' => $_format])
                , $journal->getUpdated()->format('Y-m-d')
            );
        }

        $response = new Response();
        $response->headers->add(['content-type' => 'text/xml']);
        $response->setContent($siteMapIndex);

        return $response;
    }

    public function issuesAction(Request $request, Journal $journal, $_format = 'xml')
    {
        $siteMap = new  Sitemap();
        $router = $this->get('router');

        foreach($journal->getIssues() as $issue){
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
                    'ojs_journal_rss',
                    [
                        'journal' => $journal->getId(),
                        '_format' => 'xml'
                    ]
                ),
                $journal->getUpdated()->format('Y-d-m')
            );
        }

        $response = new Response();
        $response->setContent($siteMap->toString());

        $response->headers->add(['content-type' => 'text/xml']);

        return $response;
    }

    public function institutionAction()
    {

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

        $response = new Response();
        $response->headers->add(['content-type' => 'text/xml']);

        $response->setContent($siteMap->toString());
        return $response;
    }
} 