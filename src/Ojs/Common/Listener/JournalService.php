<?php

namespace Ojs\Common\Listener;

use \Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JournalService
{

    private $em;
    private $container;
    private $session;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->em = $em;
    }

    public function getSelectedJournal()
    {
        $em = $this->container->get('doctrine')->getManager();
        $selectedJournalId = $this->session->get("selectedJournalId");
        return $em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId);
    }

}
