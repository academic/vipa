<?php

namespace Ojstr\Common\Listener;

use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Ojstr\Common\Twig;

class SecuritySwitchUserListener {

    private $context;

    public function __construct(SecurityContext $context) {
        $this->context = $context;
    }

    public function onSecuritySwitchUser(SwitchUserEvent $event) {
        $newUser = $event->getTargetUser();
        $currentUser = $this->getCurrentUser();
        // check that current user is admin
        $session = new \Symfony\Component\HttpFoundation\Session\Session();

        $userjournalroles = $session->get('userJournalRoles');
        if (is_array($userjournalroles)) {
            foreach ($userjournalroles as $rolex) {
                if ($rolex->getRole() == 'ROLE_SUPER_ADMIN') {
                    return true;
                }
            }
        }
        
        //$check = $currentUser->hasClientUsers($newUser);

        return false;
    }

    public function getCurrentUser() {
        return $this->context->getToken()->getUser();
    }

}
