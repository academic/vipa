<?php

namespace Ojs\Common\Entity;

/**
 * Class DisplayTrait
 * @package Ojs\Common\Entity
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
