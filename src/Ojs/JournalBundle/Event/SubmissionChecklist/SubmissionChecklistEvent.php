<?php

namespace Ojs\JournalBundle\Event\SubmissionChecklist;

use Ojs\JournalBundle\Entity\SubmissionChecklist;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class SubmissionChecklistEvent extends Event
{
    /** @var SubmissionChecklist */
    private $submissionChecklist;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param SubmissionChecklist $submissionChecklist
     */
    public function __construct(SubmissionChecklist $submissionChecklist)
    {
        $this->submissionChecklist = $submissionChecklist;
    }

    /**
     * @return SubmissionChecklist
     */
    public function getSubmissionChecklist()
    {
        return $this->submissionChecklist;
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
