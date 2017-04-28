<?php

namespace Vipa\CoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;

class PermissionEvent extends Event
{
    protected $controller;

    protected $attributes;

    protected $object = null;

    protected $field = null;

    protected $result = null;

    /**
     * PermissionEvent constructor.
     * @param $controller
     * @param $attributes
     * @param null $object
     * @param null $field
     */
    public function __construct($controller, $attributes, $object, $field)
    {
        $this->controller = $controller;
        $this->attributes = $attributes;
        $this->object = $object;
        $this->field = $field;
    }

    /**
     * @return null|bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param null|bool $result
     * @return PermissionEvent
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return null
     */
    public function getField()
    {
        return $this->field;
    }
}
