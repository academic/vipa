<?php

namespace Ojs\ApiBundle\Security;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\UserRepository;
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

        /** @var UserRepository $userRepo */
        $userRepo = $this->em->getRepository('OjsUserBundle:User');
        $user = $userRepo->findOneBy([
            'apiKey'=>$apiKey
        ]);

        if(!($user instanceof \Ojs\UserBundle\Entity\User)){
            return false;
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
