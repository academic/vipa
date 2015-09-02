<?php

namespace Ojs\CoreBundle\Entity;

/**
 * Class DisplayTrait
 * @package Ojs\CoreBundle\Entity
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
