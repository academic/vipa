<?php

namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\UserBundle\Entity\User;

class SoftDeleteListener
{
    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof User) {
            if (strpos($entity->getUsername(), "_deleted_") === false) {
                $entity->setUsername($entity->getUsername()."_deleted_".time());
                $entity->setEmail($entity->getEmail()."_deleted_".time());
                $entityManager->persist($entity);
                $entityManager->flush($entity);
            }
        }
    }
}
