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
            // no journal selected.
        } else {
            $journal = $this->em
                ->getRepository('OjstrJournalBundle:Journal')
                ->findOneBy(array('subdomain' => $subdomain));
            $this->journalDomain->setCurrentJournal($journal);
        }
    }

}
