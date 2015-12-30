<?php

namespace Ojs\JournalBundle\Event\Article;

use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class ArticleEvent extends Event
{
    /** @var Article */
    private $article;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Article $article
     */
    public function __construct(Article $article)
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
