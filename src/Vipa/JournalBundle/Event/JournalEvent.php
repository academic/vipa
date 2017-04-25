<?php

namespace Vipa\JournalBundle\Event;

use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalEvent extends Event
{
    /**
     * @var Response $response
     */
    protected $response;

    /**
     * @var Journal $journal
     */
    protected $journal;

    /**
     * @param Journal $journal
     */
    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
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
