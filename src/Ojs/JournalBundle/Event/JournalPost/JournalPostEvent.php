<?php

namespace Ojs\JournalBundle\Event\JournalPost;

use Ojs\JournalBundle\Entity\JournalPost;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalPostEvent extends Event
{
    /** @var JournalPost */
    private $journalPost;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param JournalPost $journalPost
     */
    public function __construct(JournalPost $journalPost)
    {
        $this->journalPost = $journalPost;
    }

    /**
     * @return JournalPost
     */
    public function getJournalPost()
    {
        return $this->journalPost;
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
