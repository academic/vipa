<?php

namespace Vipa\JournalBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class CitationEditEvent extends Event
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
     * @var int
     */
    private $citationId;

    /**
     * CitationEditEvent constructor.
     * @param int $journalId
     * @param int $articleId
     * @param int $citationId
     */
    public function __construct($journalId, $articleId, $citationId)
    {
        $this->journalId = $journalId;
        $this->articleId = $articleId;
        $this->citationId = $citationId;
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
     * @return CitationEditEvent
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
     * @return CitationEditEvent
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
     * @return CitationEditEvent
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCitationId()
    {
        return $this->citationId;
    }

    /**
     * @param int $citationId
     * @return CitationEditEvent
     */
    public function setCitationId($citationId)
    {
        $this->citationId = $citationId;
        return $this;
    }
}