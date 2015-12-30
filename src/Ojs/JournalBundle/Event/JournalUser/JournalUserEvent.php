<?php

namespace Ojs\JournalBundle\Event\JournalUser;

use Ojs\JournalBundle\Entity\JournalUser;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalUserEvent extends Event
{
    /** @var JournalUser */
    private $journalUser;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param JournalUser $journalUser
     */
    public function __construct(JournalUser $journalUser)
    {
        $this->journalUser = $journalUser;
    }

    /**
     * @return JournalUser
     */
    public function getJournalUser()
    {
        return $this->journalUser;
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
