<?php

namespace Ojs\UserBundle\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\UserBundle\Event\UserEvent;
use Ojs\UserBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class UserEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param RouterInterface $router
     * @param OjsMailer $ojsMailer
     */
    public function __construct(
        RouterInterface $router,
        OjsMailer $ojsMailer
    ) {
        $this->router = $router;
        $this->ojsMailer = $ojsMailer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'onChangePasswordCompleted',
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'onProfileEditCompleted',
        );
    }

    /**
     * @param FilterUserResponseEvent $userResponseEvent
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $userResponseEvent)
    {
        $user = $userResponseEvent->getUser();
        $this->ojsMailer->sendToUser(
            $user,
            'User Event : User Registration',
            'User Event -> User Registration Completed -> '.$user->getEmail()
        );
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onChangePasswordCompleted(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        $this->ojsMailer->sendToUser(
            $user,
            'User Event : User Change Password',
            'User Event -> User Change Password -> '.$user->getEmail()
        );
    }

    /**
     * @param GetResponseUserEvent $userResponseEvent
     */
    public function onProfileEditCompleted(GetResponseUserEvent $userResponseEvent)
    {
        $user = $userResponseEvent->getUser();
        $this->ojsMailer->sendToUser(
            $user,
            'User Event : User Profile Edit Completed',
            'User Event -> User Profile Edit Completed -> '.$user->getEmail()
        );
    }
}
