<?php

namespace Vipa\ApiBundle\Security;

use Doctrine\ORM\EntityManager;
use Vipa\UserBundle\Entity\UserRepository;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->em->getRepository('VipaUserBundle:User');
        $user = $userRepo->findOneBy(
            [
                'username' => $username,
            ]
        );

        if (!($user instanceof \Vipa\UserBundle\Entity\User)) {
            return false;
        }

        return $user;
    }

    /**
     * @param $apiKey
     * @return bool|null|object
     */
    public function loadUserByApiKey($apiKey)
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->em->getRepository('VipaUserBundle:User');
        $user = $userRepo->findOneBy(
            [
                'apiKey' => $apiKey,
            ]
        );

        if (!($user instanceof UserInterface)) {
            return false;
        }

        return $user;
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
