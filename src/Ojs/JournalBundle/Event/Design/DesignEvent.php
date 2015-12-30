<?php

namespace Ojs\JournalBundle\Event\Design;

use Ojs\JournalBundle\Entity\Design;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class DesignEvent extends Event
{
    /** @var Design */
    private $design;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Design $design
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * @return Design
     */
    public function getDesign()
    {
        return $this->design;
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
