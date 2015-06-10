<?php

namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Params\UserEventLogParams;
use Ojs\UserBundle\Entity\EventLog;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutListener implements LogoutSuccessHandlerInterface
{
    protected $em;
    protected $tokenStorage;
    protected $router;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage, Router $router)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
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
        if ($this->tokenStorage->getToken()) {
            /* @var $user User */
            $user = $this->tokenStorage->getToken()->getUser();

            //log as eventlog
            $event = new EventLog();
            $event->setEventInfo(UserEventLogParams::$USER_LOGOUT);
            $event->setIp($request->getClientIp());
            $event->setUserId($user->getId());
            $this->em->persist($event);

            $this->em->flush();
        }
        $response = new RedirectResponse($this->router->generate('login'));

        return $response;
    }
}
