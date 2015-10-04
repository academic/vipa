<?php

namespace Ojs\CoreBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Data transformer for single mode (i.e., multiple = false)
 *
 * Class EntityToPropertyTransformer
 * @package Tetranz\Select2EntityBundle\Form\DataTransformer
 */
class EntityToPropertyTransformer implements DataTransformerInterface
{
    protected $em;
    protected $className;
    protected $textProperty;

    public function __construct(EntityManager $em, $class, $textProperty)
    {
        $this->em = $em;
        $this->className = $class;
        $this->textProperty = $textProperty;
    }

    /**
     * Transform entity to json with id and text
     *
     * @param mixed $entity
     * @return string
     */
    public function transform($entity)
    {
        $data = array();
        if (null === $entity) {
            return $data;
        }
        // return the initial values as html encoded json
        $text = is_null($this->textProperty)
            ? (string)$entity
            : $entity->{'get'.$this->textProperty}();

        $data[$entity->getId()] = $text;

        return $data;
    }

    /**
     * Transform to single id value to an entity
     *
     * @param string $value
     * @return mixed|null|object
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }
        $repo = $this->em->getRepository($this->className);
        $entity = $repo->find($value);
        if (!$entity) {
            return null;
        }

        return $entity;
    }
}
