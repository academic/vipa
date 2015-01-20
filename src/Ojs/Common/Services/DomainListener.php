<?php

namespace Ojs\Common\Services;

use \Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use \Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DomainListener
{
 
    private $em;
    private $baseHost;
    private $container;

    public function __construct(EntityManager $em, $baseHost, ContainerInterface $container)
    { 
        $this->em = $em;
        $this->baseHost = $baseHost;
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        /* $request = $event->getRequest();

          $currentHost = $request->getHttpHost();
          $subdomain = str_replace('.' . $this->baseHost, '', $currentHost);
          if ($this->baseHost === $subdomain) {
          $request = $this->container->get('request');
          $routeName = $request->get('_route');
          if ($routeName == 'ojs_institution_page') {
          $params = $request->attributes->get('_route_params');
          $slug = isset($params['slug']) ? $params['slug'] : null;
          $journal = $this->em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug'=>$slug]);
          }else{
          $journal = null;
          }
          } else {
          // search o for subdomains or domains
          $qb = $this->em->getRepository('OjsJournalBundle:Journal')->createQueryBuilder('do');
          $qb->select('do')->where($qb->expr()->orX(
          $qb->expr()->eq('do.subdomain', ':domain'), $qb->expr()->eq('do.domain', ':domain')
          ))->setParameter('domain', $subdomain);
          $journal = $qb->getQuery()->getOneOrNullResult();
          /**
         * @todo show human friendly error page if there is no journal for this subdomain or domain

          }
          if ($journal) {
          $this->journalDomain->setCurrentJournal($journal);
          }
         */
    }

}
