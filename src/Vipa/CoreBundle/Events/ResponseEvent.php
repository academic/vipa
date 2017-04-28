<?php

namespace Vipa\CoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

class ResponseEvent extends Event
{
    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $data;

    /**
     * ResponseEvent constructor.
     * @param $template
     * @param array $data
     */
    public function __construct($template, $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}