<?php

namespace Vipa\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Vipa\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class EmailUserProvider implements UserProviderInterface
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NoResultException
     * @throws ORMException
     */
    public function find($id)
    {
        $q = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('VipaUserBundle:User', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $q->getSingleResult();
    }

    /**
     * @param  string       $username
     * @return mixed
     * @throws ORMException
     */
    public function loadUserByUsername($username)
    {
        $q = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('VipaUserBundle:User', 'u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin VipaUserBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    /**
     * @param  UserInterface $user
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        /** @var User $user */
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', $class)
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return 'Vipa\UserBundle\Entity\User' === $class || is_subclass_of($class, 'Vipa\UserBundle\Entity\User');
    }
}
