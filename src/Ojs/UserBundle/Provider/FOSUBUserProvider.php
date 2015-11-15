<?php

namespace Ojs\UserBundle\Provider;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseProvider;
use Ojs\UserBundle\Entity\UserOauthAccount;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseProvider
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * FOSUBUserProvider constructor.
     * @param EntityManager $entityManager
     * @param UserManagerInterface $userManager
     * @param array $properties
     */
    public function __construct(EntityManager $entityManager, UserManagerInterface $userManager, array $properties)
    {
        parent::__construct($userManager, $properties);
        $this->entityManager = $entityManager;
    }

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();

        $connection = $this->entityManager
            ->getRepository('OjsUserBundle:UserOauthAccount')
            ->findOneBy(['user' => $user, 'provider' => $service]);

        if ($connection === null) {
            $connection = new UserOauthAccount();
            $connection->setUser($user);
            $connection->setProvider($service);
        }

        $connection->setProviderUserId($response->getUsername());
        $connection->setProviderAccessToken($response->getAccessToken());
        $connection->setProviderRefreshToken($response->getRefreshToken());
        $this->entityManager->persist($connection);
        $this->entityManager->flush();
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $service = $response->getResourceOwner()->getName();

        $connection = $this->entityManager
            ->getRepository('OjsUserBundle:UserOauthAccount')
            ->findOneBy(['provider_user_id' => $username, 'provider' => $service]);

        if ($connection === null || $connection->getUser() === null) {
            $message = sprintf("User '%s' not found.", $username);
            throw new AccountNotLinkedException($message);
        }

        return $connection->getUser();
    }
}