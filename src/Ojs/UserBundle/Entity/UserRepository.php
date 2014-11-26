<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * Load by email or username
     * @param  type $username
     * @return type
     * @throws UsernameNotFoundException
     */
    public function loadUserByAny($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.roles', 'r')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf('Unable to find an active admin OjsUserBundle:User object identified by "%s".', $username);
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    /**
     * @param  string $username
     * @return \Ojs\UserBundle\Entity\User
     */
    public function loadUserByUsername($username)
    {
        try {

            $q = $this
                ->createQueryBuilder('u')
                ->select('u, r')
                ->leftJoin('u.roles', 'r')
                ->where('u.username = :username OR u.email = :email')
                ->setParameters([
                    'username' => $username,
                    'email' => $username
                ])
                ->getQuery();
            $user = $q->getSingleResult();
            return $user;
        } catch (\Exception $e) {
            $message = sprintf('Unable to find an active admin OjsUserBundle:User object identified by "%s".', $username);
            throw new UsernameNotFoundException($message, 0, $e);
        }
    }

    public function refreshUser(UserInterface $user)
    {
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
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    /**
     *
     * @param \Ojs\UserBundle\Entity\Role $role
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     * @return boolean
     */
    public function hasJournalRole(User $user, Role $role, Journal $journal)
    {
        $data = $this->getEntityManager()->createQuery(
            'SELECT u FROM OjsUserBundle:UserJournalRole u
              WHERE u.userId = :user_id
              AND u.roleId = :role_id
              AND u.journalId = :journal_id
              ')
            ->setParameter('user_id', $user->getId())
            ->setParameter('role_id', $role->getId())
            ->setParameter('journal_id', $journal->getId())
            ->getResult();
        return $data ? true : false;
    }
}
