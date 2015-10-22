<?php

namespace Ojs\JournalBundle\EventListener;

use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalEvents;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Event\ArticleSubmitEvent;
use Ojs\JournalBundle\Event\ArticleSubmitEvents;

class JournalEventListener implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $mailSender;

    /** @var string */
    private $mailSenderName;

    /** @var RouterInterface */
    private $router;

    /** @var EntityManager */
    private $em;

    /**
     * @param RouterInterface $router
     * @param \Swift_Mailer   $mailer
     * @param EntityManager   $em
     * @param string          $mailSender
     * @param string          $mailSenderName
     *
     */
    public function __construct(
        RouterInterface $router,
        \Swift_Mailer $mailer,
        EntityManager   $em,
        $mailSender,
        $mailSenderName
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->em = $em;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalEvents::JOURNAL_CHANGE => 'onJournalChange', #+
            JournalEvents::JOURNAL_USER_NEW => 'onJournalUserNew', #+
            JournalEvents::JOURNAL_USER_ROLE_CHANGE => 'onJournalUserRoleChange', #+
            JournalEvents::JOURNAL_SUBMISSION_CHECKLIST_CHANGE => 'onJournalSubmissionChecklistChange', #+
            JournalEvents::JOURNAL_SUBMISSION_FILES_CHANGE => 'onJournalSubmissionFilesChange', #+
            JournalEvents::JOURNAL_THEME_CHANGE => 'onJournalThemeChange', #+
            JournalEvents::JOURNAL_DESIGN_CHANGE => 'onJournalDesignChange', #+
            JournalEvents::JOURNAL_ARTICLE_CHANGE => 'onJournalArticleChange', #+
            ArticleSubmitEvents::SUBMIT_AFTER => 'onJournalArticleSubmitted', #+
            JournalEvents::JOURNAL_CONTACT_CHANGE => 'onJournalContactChange', #+
            JournalEvents::JOURNAL_ISSUE_CHANGE => 'onJournalIssueChange',
            JournalEvents::JOURNAL_SECTION_CHANGE => 'onJournalSectionChange',
            JournalEvents::JOURNAL_INDEX_CHANGE => 'onJournalIndexChange',
            JournalEvents::JOURNAL_BOARD_CHANGE => 'onJournalBoardChange',
            JournalEvents::JOURNAL_PERIOD_CHANGE => 'onJournalPeriodChange',
            JournalEvents::JOURNAL_POST => 'onJournalPost',
            JournalEvents::JOURNAL_ANNOUNCEMENT => 'onJournalAnnouncement',
            JournalEvents::JOURNAL_PAGE => 'onJournalPage',
        );
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Changed'
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Changed by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalUserNew(JournalEvent $event)
    {
        $user = $event->getUser();
        $message = $this->mailer->createMessage();
        $to = array($user->getEmail() => $user->getUsername());
        $message = $message
            ->setSubject(
                'Journal Event : Journal User New'
            )
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody(
                'Journal Event : Journal User New -> '. $user->getUsername(),
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalUserRoleChange(JournalEvent $event)
    {
        $user = $event->getUser();
        $message = $this->mailer->createMessage();
        $to = array($user->getEmail() => $user->getUsername());
        $message = $message
            ->setSubject(
                'Journal Event : Journal User Role Update'
            )
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody(
                'Journal Event : Journal User Role Update -> '. $user->getUsername(),
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalSubmissionChecklistChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Submission Checklist -> '. $event->getEventType()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Submission Checklist -> '.$event->getEventType().' -> by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalSubmissionFilesChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Submission Files -> '. $event->getEventType()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Submission Files -> '.$event->getEventType().' -> by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalThemeChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Theme -> '. $event->getEventType()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Theme -> '.$event->getEventType().' -> by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalDesignChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Design -> '. $event->getEventType()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Design -> '.$event->getEventType().' -> by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalArticleChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Article Change -> '. $event->getEventType()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Article Change -> '.$event->getEventType().' -> by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     * @param ArticleSubmitEvent $event
     */
    public function onJournalArticleSubmitted(ArticleSubmitEvent $event)
    {
        /** @var User $submitterUser */
        $submitterUser = $event->getArticle()->getSubmitterUser();
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Article Submitted'
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Article Submitted -> by '. $event->getArticle()->getSubmitterUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }

        $message = $this->mailer->createMessage();
        $to = array($submitterUser->getEmail() => $submitterUser->getUsername());
        $message = $message
            ->setSubject(
                'Journal Event : Journal Article Submitted Success'
            )
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody(
                'Journal Event : Journal Article Submitted Success-> by '. $submitterUser->getUsername(),
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalContactChange(JournalEvent $event)
    {
        $mailUsers = $this->getJournalRelationalUsers();
        /** @var User $user */
        foreach($mailUsers as $user){
            $message = $this->mailer->createMessage();
            $to = array($user->getEmail() => $user->getUsername());
            $message = $message
                ->setSubject(
                    'Journal Event : Journal Contact Change -> '. $event->getEventType()
                )
                ->addFrom($this->mailSender, $this->mailSenderName)
                ->setTo($to)
                ->setBody(
                    'Journal Event : Journal Contact Change -> '.$event->getEventType().' -> by -> '. $event->getUser()->getUsername(),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }

    /**
     *
     */
    public function onJournalIssueChange()
    {

    }

    /**
     *
     */
    public function onJournalSectionChange()
    {

    }

    /**
     *
     */
    public function onJournalIndexChange()
    {

    }

    /**
     *
     */
    public function onJournalBoardChange()
    {

    }

    /**
     *
     */
    public function onJournalPeriodChange()
    {

    }

    /**
     *
     */
    public function onJournalPost()
    {

    }

    /**
     *
     */
    public function onJournalAnnouncement()
    {

    }

    /**
     *
     */
    public function onJournalPage()
    {

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
        $journalManagerRole = $roleRepo->findOneByRole($journalManagerRoleName);
        $journalEditorRole = $roleRepo->findOneByRole($journalEditorRoleName);
        $journalUsers = $journalUserRepo->findAll();

        foreach ($journalUsers as $journalUser) {
            if($journalUser->getRoles()->contains($journalManagerRole)
                || $journalUser->getRoles()->contains($journalEditorRole)){
                $user = $journalUser->getUser();
                $mailUsers[] = $user;
            }
        }
        return $mailUsers;
    }
}