<?php

namespace Ojs\JournalBundle\Event\Board;

use Ojs\JournalBundle\Entity\Board;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class BoardEvent extends Event
{
    /** @var Board */
    private $board;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
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
