<?php

namespace Ojs\ApiBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getUsernameForApiKey($apiKey)
    {
        
        $user = $this->em->getRepository('OjsUserBundle:User')->findOneByApiKey($apiKey);
        if(!$user){
            throw new AccessDeniedException("User Not Found");
        }
        return $user->getUsername();
    }

    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            null,
            /**
             * @todo review here
             */
            array('ROLE_USER')
        );
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
