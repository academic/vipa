<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Article;
use Ojs\SiteBundle\Acl\JournalRoleSecurityIdentity;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class AclObjectListener
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

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Journal) {
            $journal = $entity;
            $aclManager = $this->container->get('problematic.acl_manager');
            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $builder = new MaskBuilder();
            $builder
                ->add('view')
                ->add('create');
            $viewCreate = $builder->get();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_AUTHOR'))
                ->permit($viewCreate)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_PROOFREADER'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_COPYEDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_LAYOUT_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
        } elseif ($entity instanceof Article) {
            $journal = $entity->getJournal();
            $aclManager = $this->container->get('problematic.acl_manager');

            $builder = new MaskBuilder();
            $builder
                ->add('view')
                ->add('edit');
            $viewEdit = $builder->get();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_AUTHOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_PROOFREADER'))
                ->permit($viewEdit)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_COPYEDITOR'))
                ->permit($viewEdit)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_LAYOUT_EDITOR'))
                ->permit($viewEdit)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR'))
                ->permit($viewEdit)->save();

            $aclManager->on($entity)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER'))
                ->permit($viewEdit)->save();
        }
    }
}
