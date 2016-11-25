<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Acl\AclChainManager;
use Ojs\CoreBundle\Acl\JournalRoleSecurityIdentity;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalUser;
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
            'OjsJournalBundle:Journal' => [
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
            'OjsUserBundle:User' => null,
            'OjsUserBundle:Role' => null,
            'OjsJournalBundle:Publisher' => null,
            'OjsJournalBundle:Institution' => null,
            'OjsJournalBundle:PublisherTypes' => null,
            'OjsJournalBundle:JournalContact' => null,
            'OjsJournalBundle:ContactTypes' => null,
            'OjsJournalBundle:Index' => null,
            'OjsJournalBundle:JournalApplicationFile' => null,
            'OjsJournalBundle:Author' => null,
            'OjsJournalBundle:PublisherTheme' => null,
            'OjsJournalBundle:Lang' => null,
            'OjsJournalBundle:PublisherDesign' => null,
            'OjsJournalBundle:Citation' => null,
            'OjsJournalBundle:Subject' => null,
            'OjsJournalBundle:ArticleTypes' => null,
            'OjsJournalBundle:Period' => null,
            'OjsJournalBundle:PersonTitle' => null,
            'OjsJournalBundle:JournalTheme' => null,
            'OjsAdminBundle:AdminJournalTheme' => null,
            'OjsAdminBundle:SystemSetting' => null,
            'OjsAdminBundle:AdminAnnouncement' => null,
            'OjsAdminBundle:AdminPage' => null,
            'OjsAdminBundle:AdminPost' => null,
            'OjsAdminBundle:PublisherManagers' => null,
            'OjsAdminBundle:AdminFile' => null,
            'OjsJournalBundle:MailTemplate' => null,
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
            $journals = $this->em->getRepository('OjsJournalBundle:Journal')->findAll();
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
        $authorRole = $this->em->getRepository('OjsUserBundle:Role')->findOneBy(['role' => 'ROLE_AUTHOR']);
        $journalUsers = $this->em
            ->getRepository('OjsJournalBundle:JournalUser')
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
