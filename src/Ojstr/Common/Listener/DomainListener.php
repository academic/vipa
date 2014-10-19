<?php

namespace Ojstr\Common\Listener;

use \Ojstr\Common\Model\JournalDomain;
use \Doctrine\ORM\EntityManager;
use \Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DomainListener
{

    private $journalDomain;
    private $em;
    private $baseHost;

    public function __construct(JournalDomain $journalDomain, EntityManager $em, $baseHost)
    {
        $this->journalDomain = $journalDomain;
        $this->em = $em;
        $this->baseHost = $baseHost;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $currentHost = $request->getHttpHost();
        $subdomain = str_replace('.' . $this->baseHost, '', $currentHost);
        if ($this->baseHost === $subdomain) {
            // no journal selected. this url may refer a management page under baseurl.
        } else {
            // search o for subdomains or domains 
            $qb = $this->em->getRepository('OjstrJournalBundle:Journal')->createQueryBuilder('do');
            $qb->select('do')->where($qb->expr()->orX(
                            $qb->expr()->eq('do.subdomain', ':domain'), $qb->expr()->eq('do.domain', ':domain')
            ))->setParameter('domain', $subdomain);
            $journal = $qb->getQuery()->getSingleResult();
            if ($journal) {
                $this->journalDomain->setCurrentJournal($journal);
            }
        }
    }

}
