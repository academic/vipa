<?php

namespace Ojs\UserBundle\Listeners;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use \Ojs\Common\Params\UserEventLogParams;

class LogoutListener implements LogoutSuccessHandlerInterface
{
    protected $em, $container;
    private $security;

    public function __construct(\Doctrine\ORM\EntityManager $em, Container $container = null,SecurityContext $security) {

        $this->em = $em;
        $this->container = $container;
        $this->security = $security;
    }


    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        if ($this->security->getToken()) {
            /* @var $user User */
            $user = $this->security->getToken()->getUser();

            //log as eventlog
            $event = new \Ojs\UserBundle\Entity\EventLog();
            $event->setEventInfo(UserEventLogParams::$USER_LOGOUT);
            $event->setIp($this->container->get('request')->getClientIp());
            $event->setUserId($user->getId());
            $this->em->persist($event);

            $this->em->flush();
        }
        $response =  new RedirectResponse($this->container->get('router')->generate('login'));

        return $response;
    }
}
