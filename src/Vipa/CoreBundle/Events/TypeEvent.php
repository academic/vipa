<?php

namespace Vipa\CoreBundle\Events;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\EventDispatcher\Event;

class TypeEvent extends Event
{
    /** @var AbstractType */
    private $type;

    /**
     * @param AbstractType $type
     */
    public function __construct(AbstractType $type)
    {
        $this->type = $type;
    }

    /**
     * @return AbstractType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param AbstractType $type
     * @return TypeEvent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
