<?php

namespace Vipa\AnalyticsBundle\Traits;

trait ViewableTrait
{
    /**
     * @var int
     */
    private $view;

    /**
     * @return int
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param int $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }
}