<?php

namespace Ojs\CoreBundle\EventListener;

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

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {
            //get absolute homepage url
            $url = $this->router->generate('ojs_public_index', array(), true);

            //add homepage url to the urlset named default
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $url,
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_HOURLY,
                    1
                ),
                'default'
            );
        }
    }
}