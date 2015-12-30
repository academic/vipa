<?php

namespace Ojs\JournalBundle\Event\JournalContact;

use Ojs\JournalBundle\Entity\JournalContact;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalContactEvent extends Event
{
    /** @var JournalContact */
    private $journalContact;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param JournalContact $journalContact
     */
    public function __construct(JournalContact $journalContact)
    {
        $this->journalContact = $journalContact;
    }

    /**
     * @return JournalContact
     */
    public function getJournalContact()
    {
        return $this->journalContact;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
