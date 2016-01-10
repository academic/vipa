<?php

namespace Ojs\JournalBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\JournalBundle\Entity\JournalUser;
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
            JournalEvents::JOURNAL_THEME_CHANGE => 'onJournalThemeChange',
            JournalEvents::JOURNAL_DESIGN_CHANGE => 'onJournalDesignChange',
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
}
