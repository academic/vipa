<?php

namespace Ojs\UserBundle\EventListener;

use FOS\UserBundle\Model\UserInterface;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Ojs\UserBundle\Event\UserEvents;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;

class UserEventListener implements EventSubscriberInterface
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
            UserEvents::USER_PASSWORD_RESET => 'onUserPasswordReset',
            UserEvents::USER_LOGIN => 'onUserLogin',
            UserEvents::USER_LOGOUT => 'onUserLogout',
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'onChangePasswordCompleted',
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'onProfileEditCompleted',
        );
    }

    public function onUserPasswordReset(UserEvent $userEvent)
    {

    }

    public function onUserLogin(UserEvent $userEvent)
    {

    }

    public function onUserLogout(UserEvent $userEvent)
    {

    }

    /**
     * @param FilterUserResponseEvent $userResponseEvent
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $userResponseEvent)
    {
        $user = $userResponseEvent->getUser();
        $this->sendMail(
            $user,
            'User Event : User Registration',
            'User Event -> User Registration Completed -> '. $user->getEmail()
        );
    }

    /**
     * @param GetResponseUserEvent $userResponseEvent
     */
    public function onChangePasswordCompleted(GetResponseUserEvent $userResponseEvent)
    {
        $user = $userResponseEvent->getUser();
        $this->sendMail(
            $user,
            'User Event : User Change Password',
            'User Event -> User Change Password -> '. $user->getEmail()
        );
    }

    /**
     * @param GetResponseUserEvent $userResponseEvent
     */
    public function onProfileEditCompleted(GetResponseUserEvent $userResponseEvent)
    {
        $user = $userResponseEvent->getUser();
        $this->sendMail(
            $user,
            'User Event : User Profile Edit Completed',
            'User Event -> User Profile Edit Completed -> '. $user->getEmail()
        );
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