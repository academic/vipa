<?php

namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\EventLog;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Ojs\UserBundle\Entity\User as User;
use Ojs\Common\Params\UserEventLogParams;

class LoginListener
{
    /** @var EntityManager  */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @param  InteractiveLoginEvent $event
     * @return void
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $event->getRequest();
        $token = $event->getAuthenticationToken();
        if ($token && $token->getUser() instanceof User) {
            /* @var $user User */
            // save last login
            $user = $token->getUser();
            $user->setLastlogin(new \DateTime());
            $this->em->persist($user);

            //log as eventlog
            $event = new EventLog();
            $event->setEventInfo(UserEventLogParams::$USER_LOGIN);
            $event->setIp($request->getClientIp());
            $event->setUserId($user->getId());
            $this->em->persist($event);

            $this->em->flush();
        }
    }
}
