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
            JournalEvents::JOURNAL_CHANGE => 'onJournalChange',
            JournalEvents::JOURNAL_USER_NEW => 'onJournalUserNew',
            JournalEvents::JOURNAL_USER_CHANGE => 'onJournalUserChange',
            JournalEvents::JOURNAL_SUBMISSION_CHECKLIST_CHANGE => 'onJournalSubmissionChecklistChange',
            JournalEvents::JOURNAL_SUBMISSION_FILES_CHANGE => 'onJournalSubmissionFilesChange',
            JournalEvents::JOURNAL_THEME_CHANGE => 'onJournalThemeChange',
            JournalEvents::JOURNAL_DESIGN_CHANGE => 'onJournalDesignChange',
            JournalEvents::JOURNAL_ARTICLE_CHANGE => 'onJournalArticleChange',
            JournalEvents::JOURNAL_ARTICLE_SUBMITTED => 'onJournalArticleSubmitted',
            JournalEvents::JOURNAL_CONTACT_CHANGE => 'onJournalContactChange',
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
     *
     */
    public function onJournalUserNew()
    {

    }

    /**
     *
     */
    public function onJournalUserChange()
    {

    }

    /**
     *
     */
    public function onJournalSubmissionChecklistChange()
    {

    }

    /**
     *
     */
    public function onJournalSubmissionFilesChange()
    {

    }

    /**
     *
     */
    public function onJournalThemeChange()
    {

    }

    /**
     *
     */
    public function onJournalDesignChange()
    {

    }

    /**
     *
     */
    public function onJournalArticleChange()
    {

    }

    /**
     *
     */
    public function onJournalArticleSubmitted()
    {

    }

    /**
     *
     */
    public function onJournalContactChange()
    {

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