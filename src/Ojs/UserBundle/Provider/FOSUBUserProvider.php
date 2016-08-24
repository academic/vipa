<?php

namespace Ojs\UserBundle\Provider;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseProvider;
use Ojs\UserBundle\Entity\UserOauthAccount;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseProvider
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * FOSUBUserProvider constructor.
     * @param RegistryInterface $registry
     * @param UserManagerInterface $userManager
     * @param array $properties
     */
    public function __construct(RegistryInterface $registry, UserManagerInterface $userManager, array $properties)
    {
        parent::__construct($userManager, $properties);
        $this->em = $registry->getManager();
    }

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();

        /** @var UserOauthAccount $connection */
        $connection = $this->em
            ->getRepository('OjsUserBundle:UserOauthAccount')
            ->findOneBy(['providerId' => $response->getUsername(), 'provider' => $service]);

        if($connection && $connection->getUser()->getUsername() !== $user->getUsername()) {
            $this->em->remove($connection);
            $connection = null;
        }

        if (!$connection) {
            $connection = new UserOauthAccount();
            $connection->setUser($user);
            $connection->setProvider($service);
            $connection->setProviderId($response->getUsername());
        }

        $connection->setToken($response->getAccessToken());

        $this->em->persist($connection);
        $this->em->flush();
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $email = $response->getEmail();
        $service = $response->getResourceOwner()->getName();

        /** @var UserOauthAccount $connection */
        $connection = $this->em
            ->getRepository('OjsUserBundle:UserOauthAccount')
            ->findOneBy(['providerId' => $username, 'provider' => $service]);

        if(!$connection && !empty($email)) {
            $userByEmail = $this->userManager->findUserByEmail($email);
            if($userByEmail) {
                $connection = new UserOauthAccount();
                $connection->setUser($userByEmail);
                $connection->setProvider($service);
                $connection->setProviderId($response->getUsername());
            }
        }

        if (!$connection || $connection->getUser() === null) {
            dump($response);
            die();
            $user = new User();
            $user->setEnabled(true);
            if(!empty($response->getEmail())) {
                $user->setEmail($response->getEmail());
            }
            if(!empty($response->getNickname())) {
                $user->setUsername($response->getNickname());
            }
            if(!empty($response->getUsername())) {
                $user->setUsername($response->getUsername());
            }
            $connection = new UserOauthAccount();
            $connection->setUser($user);
            $connection->setProvider($service);
            $connection->setProviderId($response->getUsername());
            $message = sprintf("User not found. Please register first and then connect the account from your profile.", $username);
            throw new AccountNotLinkedException($message);
        }

        $connection->setToken($response->getAccessToken());
        $this->em->persist($connection);
        $this->em->flush();

        return $connection->getUser();
    }
}
