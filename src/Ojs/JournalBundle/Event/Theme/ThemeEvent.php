<?php

namespace Ojs\JournalBundle\Event\Theme;

use Ojs\JournalBundle\Entity\Theme;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class ThemeEvent extends Event
{
    /** @var Theme */
    private $theme;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @param Theme $theme
     */
    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
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
