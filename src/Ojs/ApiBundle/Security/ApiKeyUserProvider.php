<?php

namespace Ojs\ApiBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Ojstr\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    public function getUsernameForApiKey($apiKey)
    {
        
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findOneByApiKey($apiKey);
        

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
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
