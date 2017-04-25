<?php

namespace Vipa\JournalBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class CitationNewEvent extends Event
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var int
     */
    private $journalId;

    /**
     * @var int
     */
    private $articleId;

    /**
     * CitationNewEvent constructor.
     * @param int $journalId
     * @param int $articleId
     */
    public function __construct($journalId, $articleId)
    {
        $this->journalId = $journalId;
        $this->articleId = $articleId;
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
     * @return CitationNewEvent
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return int
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * @param int $journalId
     * @return CitationNewEvent
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     * @return CitationNewEvent
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
        return $this;
    }
}
