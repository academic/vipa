<?php

namespace Ojs\Common\Services;

use Ojs\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

class SecuritySwitchUserListener
{

    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onSecuritySwitchUser(SwitchUserEvent $event)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        return $user->isAdmin();
    }

    public function getCurrentUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
