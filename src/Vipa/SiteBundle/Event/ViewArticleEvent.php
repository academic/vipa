<?php

namespace Vipa\SiteBundle\Event;

use Vipa\JournalBundle\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class ViewArticleEvent extends Event
{
    /**
     * @var Article
     */
    protected $article;

    /**
     * ViewArticleEvent constructor.
     * @param Article $article
     */
    public function __construct($article)
    {
        $this->article = $article;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}