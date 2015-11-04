<?php

namespace Ojs\CoreBundle\EventListener;

use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Routing\RouterInterface;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Doctrine\ORM\EntityManager;

class SitemapListener implements SitemapListenerInterface
{
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
        //@todo this list can be extend
        $mainLinkRoutes = [
            'login',
            'fos_user_registration_register',
        ];
        foreach($mainLinkRoutes as $route){
            $event->getGenerator()->addUrl(
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
        $journals = $this->em->getRepository('OjsJournalBundle:Journal')->findBy([
            'published' => true
        ]);
        foreach($journals as $journal){
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $this->router->generate('ojs_journal_index', [
                        'publisher' => $journal->getPublisher()->getSlug(),
                        'slug' => $journal->getSlug()
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
        foreach($issues as $issue){
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $this->router->generate('ojs_issue_page', [
                        'publisher'     => $journal->getPublisher()->getSlug(),
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
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $this->router->generate('ojs_article_page', [
                        'publisher'     => $journal->getPublisher()->getSlug(),
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