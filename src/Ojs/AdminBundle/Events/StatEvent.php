<?php

namespace Ojs\AdminBundle\Events;

use Symfony\Component\EventDispatcher\Event;

class StatEvent extends Event
{

    /**
     * @var []
     */
    protected $data;

    /**
     * @var []
     */
    protected $json;

    public function __construct($json = [], $data = [])
    {
        $this->data = $data;
        $this->json = $json;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param [] $data
     *
     * @return $this
     *
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param [] $json
     * @return $this
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }
}
