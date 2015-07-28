<?php

namespace Ojs\JournalBundle\Event;

namespace OkulBilisim\ApplicationBundle\Event;

use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ArticleSubmitEvent extends Event
{
    /** @var Article */
    private $article;

    /**
     * @var Request $request
     */
    private $request;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Article $article
     * @param Request     $request
     */
    public function __construct(Article $article, Request $request, Response $response)
    {
        $this->article = $article;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
