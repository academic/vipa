<?php

namespace Vipa\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Acl\AclChainManager;
use Vipa\CoreBundle\Acl\JournalRoleSecurityIdentity;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\JournalUser;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class AclFixer
{
    /** @var  EntityManager */
    protected $em;

    /** @var  AclChainManager */
    protected $aclChainManager;

    /**
     * AclFixer constructor.
     * @param RegistryInterface $registry
     * @param AclChainManager $aclChainManager
     */
    public function __construct(RegistryInterface $registry, AclChainManager $aclChainManager)
    {
        $this->em = $registry->getManager();
        $this->aclChainManager = $aclChainManager;
    }

    public function insertAcls()
    {
        $classes = [
            'VipaJournalBundle:Journal' => [
                'adminMenu',
                'stats',
                'boards',
                'sections',
                'issues',
                'articles',
                'design',
                'contacts',
                'block',
                'theme',
                'index',
                'checklist',
                'file',
                'mailTemplate',
                'report',
                'userRole',
                'citation',
                'steps',
                'postTemplates',
                'announcements',
                'pages',
                'posts',
                'submissionSettings',
                'mailSettings',
                'reviewForms',
                'files'
            ],
            'VipaUserBundle:User' => null,
            'VipaUserBundle:Role' => null,
            'VipaJournalBundle:Publisher' => null,
            'VipaJournalBundle:Institution' => null,
            'VipaJournalBundle:PublisherTypes' => null,
            'VipaJournalBundle:JournalContact' => null,
            'VipaJournalBundle:ContactTypes' => null,
            'VipaJournalBundle:Index' => null,
            'VipaJournalBundle:JournalApplicationFile' => null,
            'VipaJournalBundle:Author' => null,
            'VipaJournalBundle:PublisherTheme' => null,
            'VipaJournalBundle:Lang' => null,
            'VipaJournalBundle:PublisherDesign' => null,
            'VipaJournalBundle:Citation' => null,
            'VipaJournalBundle:Subject' => null,
            'VipaJournalBundle:ArticleTypes' => null,
            'VipaJournalBundle:Period' => null,
            'VipaJournalBundle:PersonTitle' => null,
            'VipaJournalBundle:JournalTheme' => null,
            'VipaAdminBundle:AdminJournalTheme' => null,
            'VipaAdminBundle:SystemSetting' => null,
            'VipaAdminBundle:AdminAnnouncement' => null,
            'VipaAdminBundle:AdminPage' => null,
            'VipaAdminBundle:AdminPost' => null,
            'VipaAdminBundle:PublisherManagers' => null,
            'VipaAdminBundle:AdminFile' => null,
            'VipaJournalBundle:MailTemplate' => null,
        ];
        foreach ($classes as $className => $fields) {
            $realClassName = $this->em->getRepository($className)->getClassName();
            $this->aclChainManager->on($realClassName)->to('ROLE_ADMIN')->permit(MaskBuilder::MASK_OWNER)->save();
            if (is_array($fields) && !empty($fields)) {
                foreach ($fields as $field) {
                    $this->aclChainManager->on($realClassName)->field($field)->to('ROLE_ADMIN')->permit(
                        MaskBuilder::MASK_OWNER
                    )->save();
                }
            }
        }
    }

    public function fixAcl(Journal $journal = null)
    {
        $viewEdit = (new MaskBuilder())
            ->add('view')
            ->add('edit')->get();
        $viewEditDelete = (new MaskBuilder())
            ->add('view')
            ->add('edit')
            ->add('delete')->get();

        if (!$journal) {
            /** @var Journal[] $journals */
            $journals = $this->em->getRepository('VipaJournalBundle:Journal')->findAll();
        } else {
            $journals = array($journal);
        }
        foreach ($journals as $journal) {
            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('boards')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('stats')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('sections')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('contacts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('block')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('design')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('theme')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('index')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('submissionSettings')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('checklist')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('file')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('mailTemplate')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('report')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('userRole')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('articles')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('steps')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('announcements')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('reviewForms')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER')
            )->permit(MaskBuilder::MASK_OWNER)->save();

            $this->aclChainManager->on($journal)
                ->field('mailSettings')
                ->to(new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $this->aclChainManager->on($journal)
                ->field('files')
                ->to(new JournalRoleSecurityIdentity($journal, 'ROLE_JOURNAL_MANAGER'))
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('stats')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('sections')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('block')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('index')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('checklist')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('file')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('mailTemplate')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('articles')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('announcements')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('mailSettings')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('submissionSettings')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('reviewForms')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('stats')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('sections')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('block')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('index')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('checklist')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('file')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('mailTemplate')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('articles')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('announcements')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('mailSettings')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('submissionSettings')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();
            $this->aclChainManager->on($journal)->field('reviewForms')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_CO_EDITOR')
            )
                ->permit(MaskBuilder::MASK_OWNER)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_AUTHOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('articles')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_AUTHOR')
            )
                ->permit(MaskBuilder::MASK_CREATE)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_PROOFREADER'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_PROOFREADER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_COPYEDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_COPYEDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_LAYOUT_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_LAYOUT_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $this->aclChainManager->on($journal)->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $this->aclChainManager->on($journal)->field('reviewForms')
                ->to(new JournalRoleSecurityIdentity($journal, 'ROLE_SECTION_EDITOR'))
                ->permit(MaskBuilder::MASK_VIEW)->save();

            $this->aclChainManager->on($journal)->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('adminMenu')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit(MaskBuilder::MASK_VIEW)->save();
            $this->aclChainManager->on($journal)->field('boards')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('sections')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('contacts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('block')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('design')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('theme')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('pages')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('posts')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('index')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('checklist')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('file')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('mailTemplate')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
            $this->aclChainManager->on($journal)->field('issues')->to(
                new JournalRoleSecurityIdentity($journal, 'ROLE_SUBSCRIPTION_MANAGER')
            )
                ->permit($viewEdit)->save();
        }

        // Every journal manager and editor must be an author too

        /* @var JournalUser[] $journalUsers */
        $authorRole = $this->em->getRepository('VipaUserBundle:Role')->findOneBy(['role' => 'ROLE_AUTHOR']);
        $journalUsers = $this->em
            ->getRepository('VipaJournalBundle:JournalUser')
            ->findByRoles(array('ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR', 'ROLE_CO_EDITOR'));

        foreach ($journalUsers as $journalUser) {
            if (!$journalUser->getRoles()->contains($authorRole)) {
                $journalUser->getRoles()->add($authorRole);
                $this->em->persist($journalUser);

            }
        }

        $this->em->flush();
    }
}
