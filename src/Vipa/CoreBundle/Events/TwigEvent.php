<?php

namespace Vipa\CoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

class TwigEvent extends Event
{
    /**
     * @var array $options
     */
    private $options;

    /**
     * @var string $template
     */
    private $template;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
}
