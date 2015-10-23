<?php

namespace Ojs\AdminBundle\EventListener;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Ojs\AdminBundle\Events\AdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class AdminEventListener implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $mailSender;

    /** @var string */
    private $mailSenderName;

    /** @var RouterInterface */
    private $router;

    /**
     * @param RouterInterface $router
     * @param \Swift_Mailer   $mailer
     * @param string          $mailSender
     * @param string          $mailSenderName
     *
     */
    public function __construct(
        RouterInterface $router,
        \Swift_Mailer $mailer,
        $mailSender,
        $mailSenderName
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'onUserPassChange',
            AdminEvents::USER_CHANGE => 'onUserChange',
            AdminEvents::JOURNAL_CONTACT_CHANGE => 'onJournalContactChange',
            AdminEvents::JOURNAL_APPLICATION_HAPPEN => 'onJournalApplicationHappen',
            AdminEvents::JOURNAL_CHANGE => 'onJournalChange',
            AdminEvents::PUBLISHER_APPLICATION_HAPPEN => 'onPublisherApplicationHappen',
            AdminEvents::PUBLISHER_MANAGER_CHANGE => 'onPublisherManagerChange',
            AdminEvents::PUBLISHER_CHANGE => 'onPublisherChange',
            AdminEvents::SUBJECT_CHANGE => 'onSubjectChange',
            AdminEvents::SETTINGS_CHANGE => 'onSettingsChange',
        );
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onUserPassChange(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        $this->sendMail(
            $user,
            'User Event : User Change Password',
            'User Event -> User Change Password -> '. $user->getEmail()
        );
    }

    /**
     *
     */
    public function onUserChange()
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
    public function onJournalApplicationHappen()
    {

    }

    /**
     *
     */
    public function onJournalChange()
    {

    }

    /**
     *
     */
    public function onPublisherApplicationHappen()
    {

    }

    /**
     *
     */
    public function onPublisherManagerChange()
    {

    }

    /**
     *
     */
    public function onPublisherChange()
    {

    }

    /**
     *
     */
    public function onSubjectChange()
    {

    }

    /**
     *
     */
    public function onSettingsChange()
    {

    }

    /**
     * @param UserInterface $user
     * @param string $subject
     * @param string $body
     */
    private function sendMail(UserInterface $user, $subject, $body)
    {
        $message = $this->mailer->createMessage();
        $to = array($user->getEmail() => $user->getUsername());
        $message = $message
            ->setSubject($subject)
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }
}