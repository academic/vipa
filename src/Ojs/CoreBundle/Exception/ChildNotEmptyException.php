<?php

namespace Ojs\CoreBundle\Exception;


class ChildNotEmptyException extends \Exception
{
    private $entity;

    private $mapping;

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return ChildNotEmptyException
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param mixed $mapping
     * @return ChildNotEmptyException
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;

        return $this;
    }
}
