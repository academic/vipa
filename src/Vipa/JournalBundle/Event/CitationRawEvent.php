<?php

namespace Vipa\JournalBundle\Event;

use Vipa\JournalBundle\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class CitationRawEvent extends Event
{
    /** @var Article */
    private $article;

    /** @var string */
    private $raw;

    /**
     * CitationRawEvent constructor.
     * @param Article $article
     * @param string $raw
     */
    public function __construct(Article $article, $raw)
    {
        $this->article = $article;
        $this->raw = $raw;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return CitationRawEvent
     */
    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * @param string $raw
     * @return CitationRawEvent
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
        return $this;
    }
}