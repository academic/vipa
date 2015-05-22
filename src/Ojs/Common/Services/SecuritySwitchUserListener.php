<?php

namespace Ojs\Common\Services;

use Ojs\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Session\Session;
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
        $newUser = $event->getTargetUser();
        // check that current user is admin
        $session = new Session();

        /** @var Role[] $userJournalRoles */
        $userJournalRoles = $session->get('userJournalRoles');
        if ($newUser && is_array($userJournalRoles)) {
            foreach ($userJournalRoles as $rolex) {
                if ($rolex->getRole() == 'ROLE_SUPER_ADMIN') {
                    return true;
                }
            }
        }

        //$check = $currentUser->hasClientUsers($newUser);

        return false;
    }

    public function getCurrentUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
