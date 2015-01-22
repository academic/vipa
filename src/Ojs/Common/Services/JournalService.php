<?php

namespace Ojs\Common\Services;

use \Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Common methods for journal
 */
class JournalService
{

    private $em;
    private $container;
    private $session;

    /**
     * 
     * @param ContainerInterface $container
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->em = $em;
    }

    /**
     * get user's current selected journal
     * @return \Ojs\JournalBundle\Entity\Journal
     * @throws HttpException
     */
    public function getSelectedJournal()
    {
        $em = $this->container->getDoctrine()->getManager();
        $selectedJournalId = $this->session->get("selectedJournalId");
        $selectedJournal = $selectedJournalId ? $em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId) : null;
        if ($selectedJournal) {
            return $selectedJournal;
        } else {
            throw new HttpException(404, "mustselectjournal");
        }
    }

}
