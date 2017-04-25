<?php

namespace Vipa\JournalBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AclOrmListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Journal) {
            $this->container->get('core.acl_fixer')->fixAcl($entity);
        }
    }
}
