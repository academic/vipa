<?php

namespace Ojs\CoreBundle\Exception;


class HasRelationException extends \Exception
{
    private $entityName;

    private $entity;

    private $mapping;

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param mixed $entityName
     * @return HasRelationException
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return HasRelationException
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
     * @return HasRelationException
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;

        return $this;
    }
}
