<?php

namespace Ojs\JournalBundle\Event;

use Ojs\JournalBundle\Entity\JournalItemInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class JournalItemEvent extends Event implements ItemEventInterface
{
    /**
     * @var Response $response
     */
    protected $response;

    /** @var JournalItemInterface */
    private $item;

    /**
     * @param JournalItemInterface $item
     */
    public function __construct(JournalItemInterface $item)
    {
        $this->item = $item;
    }

    /**
     * @return JournalItemInterface
     */
    public function getItem()
    {
        return $this->item;
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
