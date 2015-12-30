<?php

namespace Ojs\JournalBundle\Event\Section;

use Ojs\JournalBundle\Entity\Section;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class SectionEvent extends Event
{
    /** @var Section */
    private $section;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
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
