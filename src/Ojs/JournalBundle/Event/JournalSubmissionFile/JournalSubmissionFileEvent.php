<?php

namespace Ojs\JournalBundle\Event\JournalSubmissionFile;

use Ojs\JournalBundle\Entity\JournalSubmissionFile;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalSubmissionFileEvent extends Event
{
    /** @var JournalSubmissionFile */
    private $journalSubmissionFile;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param JournalSubmissionFile $journalSubmissionFile
     */
    public function __construct(JournalSubmissionFile $journalSubmissionFile)
    {
        $this->journalSubmissionFile = $journalSubmissionFile;
    }

    /**
     * @return JournalSubmissionFile
     */
    public function getJournalSubmissionFile()
    {
        return $this->journalSubmissionFile;
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
