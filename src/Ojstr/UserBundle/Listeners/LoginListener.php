<?php

namespace Ojstr\UserBundle\Listeners;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Ojstr\UserBundle\Entity\User as User;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class LoginListener
{
    protected $em, $container;

    public function __construct(\Doctrine\ORM\EntityManager $em, Container $container = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     *
     * @param  \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     * @return void
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        if ($token && $token->getUser() instanceof User) {
            /* @var $user \User */
            // save last login
            $user = $token->getUser();
            $user->setLastlogin(new \DateTime());
            $this->em->persist($user);

            //log as eventlog
            $event = new \Ojstr\UserBundle\Entity\EventLog();
            $event->setEventInfo(\Ojstr\Common\Params\UserEventLogParams::$USER_LOGIN);
            $event->setIp($this->container->get('request')->getClientIp());
            $event->setUserId($user->getId());
            $this->em->persist($event);

            $this->em->flush();
        }
    }

}
