<?php

namespace Ojs\UserBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * Class UsersToPropertyTransformer
 * @package Ojs\UserBundle\Form\DataTransformer
 */
class UsersToPropertyTransformer implements DataTransformerInterface
{
    protected $em;
    protected $textProperty;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transform initial entities as json with id and text
     *
     * @param User[]|null $users
     * @return string
     */
    public function transform($users)
    {
        if(is_null($users)) {
            return array();
        }
        // return an array of initial values as html encoded json
        $data = array();

        foreach($users as $user) {
            $data[$user->getId()] = (string)$user;
        }

        return $data;
    }

    /**
     *
     * @param array $values
     * @return User[]|ArrayCollection
     */
    public function reverseTransform($values)
    {
        if (empty($values)) {
            return new ArrayCollection();
        }

        // get multiple entities with one query
        $entities = $this->em->createQueryBuilder()
            ->select('u')
            ->from('OjsUserBundle:User', 'u')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $values)
            ->getQuery()
            ->getResult();

        return $entities;
    }
}
