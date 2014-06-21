<?php

namespace Ojstr\WorkflowBundle\Tests\Fixtures;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FakeSecurityContext implements SecurityContextInterface {

    private $authenticatedUser;

    public function __construct($authenticatedUser) {
        $this->authenticatedUser = $authenticatedUser;
    }

    public function getToken() {
        
    }

    public function setToken(TokenInterface $token = null) {
        
    }

    public function isGranted($attributes, $object = null) {
        return $this->authenticatedUser;
    }

}
