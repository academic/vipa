<?php

namespace Ojs\JournalBundle\Event\Index;

use Ojs\JournalBundle\Entity\Index;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class IndexEvent extends Event
{
    /** @var Index */
    private $index;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Index $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
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
