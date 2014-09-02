<?php

namespace Ojstr\Common\Listener;

use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Core\SecurityContext;

class SecuritySwitchUserListener {

    private $context;

    public function __construct(SecurityContext $context) {
        $this->context = $context;
    }

    public function onSecuritySwitchUser(SwitchUserEvent $event) {
        $newUser = $event->getTargetUser();
        $currentUser = $this->getCurrentUser();
        echo "<pre>";
        print_r($currentUser->getUsername());
        exit();
        // check that current user is admin 
        // if current user is not admin , check that newUser has given attorneyship to current user
    }

    public function getCurrentUser() {
        return $this->context->getToken()->getUser();
    }

}
