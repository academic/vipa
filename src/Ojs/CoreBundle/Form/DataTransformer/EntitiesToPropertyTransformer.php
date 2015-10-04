<?php

namespace Ojs\CoreBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * Class EntitiesToPropertyTransformer
 * @package Ojs\CoreBundle\Form\DataTransformer
 */
class EntitiesToPropertyTransformer implements DataTransformerInterface
{
    protected $em;
    protected $className;
    protected $textProperty;

    public function __construct(EntityManager $em, $class, $textProperty = null)
    {
        $this->em = $em;
        $this->className = $class;
        $this->textProperty = $textProperty;
    }

    /**
     * Transform initial entities as json with id and text
     *
     * @param mixed $entities
     * @return string
     */
    public function transform($entities)
    {
        if (is_null($entities) || count($entities) === 0) {
            return array();
        }

        // return an array of initial values as html encoded json
        $data = array();

        foreach ($entities as $entity) {
            $text = is_null($this->textProperty)
                ? (string)$entity
                : $entity->{'get'.$this->textProperty}();

            $data[$entity->getId()] = $text;
        }

        return $data;
    }

    /**
     *
     * @param array $values
     * @return ArrayCollection
     */
    public function reverseTransform($values)
    {
        if (empty($values)) {
            return new ArrayCollection();
        }

        // get multiple entities with one query
        $entities = $this->em->createQueryBuilder()
            ->select('entity')
            ->from($this->className, 'entity')
            ->where('entity.id IN (:ids)')
            ->setParameter('ids', $values)
            ->getQuery()
            ->getResult();

        return $entities;
    }
}
