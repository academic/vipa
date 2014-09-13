<?php

namespace Ojstr\Common\Listener;

use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Core\SecurityContext;

class SecuritySwitchUserListener
{

    private $context;

    public function __construct(SecurityContext $context)
    {
        $this->context = $context;
    }

    public function onSecuritySwitchUser(SwitchUserEvent $event)
    {
        $newUser = $event->getTargetUser();
        $currentUser = $this->getCurrentUser();
        // check that current user is admin 
        $check = $currentUser->hasClientUsers($newUser);
        return $check;
    }

    public function getCurrentUser()
    {
        return $this->context->getToken()->getUser();
    }

}
