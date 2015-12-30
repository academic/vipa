<?php

namespace Ojs\JournalBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Event\Article\ArticleEvent;
use Ojs\JournalBundle\Event\Article\ArticleEvents;
use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalEvents;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class JournalEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param RouterInterface $router
     * @param EntityManager   $em
     * @param OjsMailer       $ojsMailer
     */
    public function __construct(
        RouterInterface $router,
        EntityManager $em,
        OjsMailer $ojsMailer
    ) {
        $this->router = $router;
        $this->em = $em;
        $this->ojsMailer = $ojsMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalEvents::JOURNAL_CHANGE => 'onJournalChange',
            JournalEvents::JOURNAL_USER_NEW => 'onJournalUserNew',
            JournalEvents::JOURNAL_USER_ROLE_CHANGE => 'onJournalUserRoleChange',
            JournalEvents::JOURNAL_SUBMISSION_CHECKLIST_CHANGE => 'onJournalSubmissionChecklistChange',
            JournalEvents::JOURNAL_SUBMISSION_FILES_CHANGE => 'onJournalSubmissionFilesChange',
            JournalEvents::JOURNAL_THEME_CHANGE => 'onJournalThemeChange',
            JournalEvents::JOURNAL_DESIGN_CHANGE => 'onJournalDesignChange',
            JournalEvents::JOURNAL_ARTICLE_CHANGE => 'onJournalArticleChange',
            ArticleEvents::POST_SUBMIT => 'onJournalArticleSubmitted',
            JournalEvents::JOURNAL_CONTACT_CHANGE => 'onJournalContactChange',
            JournalEvents::JOURNAL_ISSUE_CHANGE => 'onJournalIssueChange',
            JournalEvents::JOURNAL_SECTION_CHANGE => 'onJournalSectionChange',
            JournalEvents::JOURNAL_INDEX_CHANGE => 'onJournalIndexChange',
            JournalEvents::JOURNAL_BOARD_CHANGE => 'onJournalBoardChange',
            JournalEvents::JOURNAL_PERIOD_CHANGE => 'onJournalPeriodChange', #analyze
            JournalEvents::JOURNAL_POST_CHANGE => 'onJournalPostChange',
            JournalEvents::JOURNAL_ANNOUNCEMENT_CHANGE => 'onJournalAnnouncementChange',
            JournalEvents::JOURNAL_PAGE_CHANGE => 'onJournalPageChange',
        );
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Changed',
                'Journal Event : Journal Changed by -> '.$event->getUser()->getUsername()
            );
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection | User[]
     */
    private function getJournalRelationalUsers()
    {
        $mailUsers = [];
        $journalManagerRoleName = 'ROLE_JOURNAL_MANAGER';
        $journalEditorRoleName = 'ROLE_EDITOR';
        $roleRepo = $this->em->getRepository('OjsUserBundle:Role');
        $journalUserRepo = $this->em->getRepository('OjsJournalBundle:JournalUser');
        $journalManagerRole = $roleRepo->findOneBy(array('role' => $journalManagerRoleName));
        $journalEditorRole = $roleRepo->findOneBy(array('role' => $journalEditorRoleName));
        $journalUsers = $journalUserRepo->findAll();

        foreach ($journalUsers as $journalUser) {
            if ($journalUser->getRoles()->contains($journalManagerRole)
                || $journalUser->getRoles()->contains($journalEditorRole)
            ) {
                $user = $journalUser->getUser();
                $mailUsers[] = $user;
            }
        }

        return $mailUsers;
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalUserNew(JournalEvent $event)
    {
        $user = $event->getUser();
        $this->ojsMailer->sendToUser(
            $user,
            'Journal Event : Journal User New',
            'Journal Event : Journal User New -> '.$user->getUsername()
        );
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalUserRoleChange(JournalEvent $event)
    {
        $user = $event->getUser();
        $this->ojsMailer->sendToUser(
            $user,
            'Journal Event : Journal User Role Update',
            'Journal Event : Journal User Role Update -> '.$user->getUsername()
        );
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalSubmissionChecklistChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Submission Checklist Change -> '.$event->getEventType(),
                'Journal Event : Journal Submission Checklist Change -> '.$event->getEventType(
                ).' -> by '.$event->getUser()->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalSubmissionFilesChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Submission Files Change -> '.$event->getEventType(),
                'Journal Event : Journal Submission Files Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalThemeChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Theme Change -> '.$event->getEventType(),
                'Journal Event : Journal Theme Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalDesignChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Design Change -> '.$event->getEventType(),
                'Journal Event : Journal Design Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalArticleChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Article Change -> '.$event->getEventType(),
                'Journal Event : Journal Article Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param ArticleEvent $event
     */
    public function onJournalArticleSubmitted(ArticleEvent $event)
    {
        $submitterUser = $event->getArticle()->getSubmitterUser();
        $mailUsers = $this->getJournalRelationalUsers();
        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Article Submitted',
                'Journal Event : Journal Article Submitted -> by '.$event->getArticle()->getSubmitterUser(
                )->getUsername()
            );
        }
        //send mail to author
        $this->ojsMailer->sendToUser(
            $submitterUser,
            'Journal Event : Journal Article Submitted Success',
            'Journal Event : Journal Article Submitted Success-> by '.$submitterUser->getUsername()
        );
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalContactChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Contact Change -> '.$event->getEventType(),
                'Journal Event : Journal Contact Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalIssueChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Issue Change -> '.$event->getEventType(),
                'Journal Event : Journal Issue Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalSectionChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Section Change -> '.$event->getEventType(),
                'Journal Event : Journal Section Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalIndexChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Index Change -> '.$event->getEventType(),
                'Journal Event : Journal Index Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalBoardChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Board Change -> '.$event->getEventType(),
                'Journal Event : Journal Board Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalPeriodChange(JournalEvent $event)
    {
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalPostChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Post Change -> '.$event->getEventType(),
                'Journal Event : Journal Post Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalAnnouncementChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Announcement Change -> '.$event->getEventType(),
                'Journal Event : Journal Announcement Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalPageChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Page Change -> '.$event->getEventType(),
                'Journal Event : Journal Page Change -> '.$event->getEventType().' -> by '.$event->getUser(
                )->getUsername()
            );
        }
    }
}
