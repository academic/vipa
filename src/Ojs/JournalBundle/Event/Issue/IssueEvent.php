<?php

namespace Ojs\JournalBundle\Event\Issue;

use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class IssueEvent extends Event
{
    /** @var Issue */
    private $issue;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Issue $issue
     */
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
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
