<?php

namespace Vipa\UserBundle\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Vipa\CoreBundle\Service\Mailer;
use Vipa\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class UserEventListener implements EventSubscriberInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var Mailer */
    private $vipaMailer;

    /**
     * @param RouterInterface $router
     * @param Mailer $vipaMailer
     */
    public function __construct(
        RouterInterface $router,
        Mailer $vipaMailer
    ) {
        $this->router = $router;
        $this->vipaMailer = $vipaMailer;
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
        $getMailEvent = $this->vipaMailer->getTemplateByEvent(FOSUserEvents::REGISTRATION_COMPLETED);
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
        $template = $this->vipaMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->vipaMailer->sendToUser(
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
        $getMailEvent = $this->vipaMailer->getTemplateByEvent(FOSUserEvents::CHANGE_PASSWORD_COMPLETED);
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
        $template = $this->vipaMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->vipaMailer->sendToUser(
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
        $getMailEvent = $this->vipaMailer->getTemplateByEvent(FOSUserEvents::PROFILE_EDIT_COMPLETED);
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
        $template = $this->vipaMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->vipaMailer->sendToUser(
            $user,
            $getMailEvent->getSubject(),
            $template
        );
    }
}
