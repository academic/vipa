<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Ojs\JournalBundle\Document\ArticleSubmissionProgress;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class AclOdmListener
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
        $document = $args->getDocument();

        if ($document instanceof ArticleSubmissionProgress) {
            $aclManager = $this->container->get('problematic.acl_manager');
            $aclManager->on($document)->permit(MaskBuilder::MASK_OWNER)->save();
        }
    }
}
