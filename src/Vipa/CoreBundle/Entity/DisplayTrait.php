<?php

namespace Vipa\CoreBundle\Entity;

/**
 * Class DisplayTrait
 * @package Vipa\CoreBundle\Entity
 */
trait DisplayTrait
{
    /**
     * get object vars
     *
     * @return array
     */
    public function display()
    {
        return get_object_vars($this);
    }
}
