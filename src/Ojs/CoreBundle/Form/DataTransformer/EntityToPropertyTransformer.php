<?php

namespace Ojs\CoreBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $accessor = PropertyAccess::createPropertyAccessor();

        $text = is_null($this->textProperty)
            ? (string)$entity
            : $accessor->getValue($entity, $this->textProperty);

        $data[$accessor->getValue($entity, 'id')] = $text;

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
