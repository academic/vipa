<?php

namespace Ojs\JournalBundle\Event\JournalPage;

use Ojs\JournalBundle\Entity\JournalPage;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalPageEvent extends Event
{
    /** @var JournalPage */
    private $journalPage;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param JournalPage $journalPage
     */
    public function __construct(JournalPage $journalPage)
    {
        $this->journalPage = $journalPage;
    }

    /**
     * @return JournalPage
     */
    public function getJournalPage()
    {
        return $this->journalPage;
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
