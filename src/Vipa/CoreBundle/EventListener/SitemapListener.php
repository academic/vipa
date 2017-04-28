<?php

namespace Vipa\CoreBundle\EventListener;

use Vipa\JournalBundle\Entity\Issue;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\Routing\RouterInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Doctrine\ORM\EntityManager;

class SitemapListener implements SitemapListenerInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    public function __construct(
        RouterInterface $router,
        EntityManager   $em
    )
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * @param SitemapPopulateEvent $event
     * @return SitemapPopulateEvent
     */
    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $event = $this->generateMainLinks($event);
        $event = $this->generateJournalLinks($event);
        return $event;
    }

    /**
     * @param SitemapPopulateEvent $event
     * @return SitemapPopulateEvent
     */
    private function generateMainLinks(SitemapPopulateEvent $event)
    {
        $mainLinkRoutes = [
            'login',
            'fos_user_registration_register',
            'vipa_site_people_index',
            'vipa_tags_cloud',
            'vipa_search_advanced',
            'vipa_public_index',
            'vipa_apply_journal',
            'vipa_apply_publisher',
            'vipa_site_explore_publisher',
            'vipa_site_explore_index',
        ];
        foreach($mainLinkRoutes as $route){
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->router->generate($route, [], true),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_MONTHLY,
                    1
                ),
                'main_links'
            );
        }
        return $event;
    }

    private function generateJournalLinks(SitemapPopulateEvent $event)
    {
        $journals = $this->em->getRepository('VipaJournalBundle:Journal')->findAll();
        foreach($journals as $journal){
            if(!$journal->isIndexable()){
                continue;
            }
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->router->generate('vipa_journal_index', [
                        'slug'          => $journal->getSlug()
                    ], true),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_WEEKLY,
                    1
                ),
                'journals-'.$journal->getSlug()
            );
            $event = $this->generateIssueLinks($event, $journal);
        }
        return $event;
    }

    /**
     * @param SitemapPopulateEvent $event
     * @param Journal $journal
     * @return SitemapPopulateEvent
     */
    private function generateIssueLinks(SitemapPopulateEvent $event,Journal $journal)
    {
        $issues = $journal->getIssues();
        /** @var Issue $issue */
        foreach($issues as $issue){
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $this->router->generate('vipa_issue_page', [
                        'journal_slug'  => $journal->getSlug(),
                        'id'            => $issue->getId()
                    ], true),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_WEEKLY,
                    1
                ),
                'journals-'.$journal->getSlug()
            );
            $event = $this->generateArticleLinks($event, $issue);
        }
        return $event;
    }

    /**
     * @param SitemapPopulateEvent $event
     * @param Issue $issue
     * @return SitemapPopulateEvent
     */
    private function generateArticleLinks(SitemapPopulateEvent $event,Issue $issue)
    {
        $articles = $issue->getArticles();
        $journal = $issue->getJournal();
        foreach($articles as $article){
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->router->generate('vipa_article_page', [
                        'slug'          => $journal->getSlug(),
                        'issue_id'      => $issue->getId(),
                        'article_id'    => $article->getId()
                    ], true),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_WEEKLY,
                    1
                ),
                'journals-'.$journal->getSlug()
            );
        }
        return $event;
    }
}
