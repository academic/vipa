<?php

namespace Ojs\UserBundle\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\UserBundle\Entity\User;
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
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(FOSUserEvents::REGISTRATION_COMPLETED);
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        $user = $event->getUser();
        $transformParams = [
            'user.username'     => $user->getUsername(),
            'user.fullName'     => $user->getFullName(),
            'user.mail'         => $user->getEmail(),
        ];
        $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->ojsMailer->sendToUser(
            $user,
            $getMailEvent->getSubject(),
            $template
        );
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onChangePasswordCompleted(GetResponseUserEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(FOSUserEvents::CHANGE_PASSWORD_COMPLETED);
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        $user = $event->getUser();
        $transformParams = [
            'user.username'     => $user->getUsername(),
            'user.fullName'     => $user->getFullName(),
            'user.mail'         => $user->getEmail(),
        ];
        $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->ojsMailer->sendToUser(
            $user,
            $getMailEvent->getSubject(),
            $template
        );
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onProfileEditCompleted(GetResponseUserEvent $event)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(FOSUserEvents::PROFILE_EDIT_COMPLETED);
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        $user = $event->getUser();
        $transformParams = [
            'user.username'     => $user->getUsername(),
            'user.fullName'     => $user->getFullName(),
            'user.mail'         => $user->getEmail(),
        ];
        $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->ojsMailer->sendToUser(
            $user,
            $getMailEvent->getSubject(),
            $template
        );
    }
}
