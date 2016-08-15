<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * @param $query
     * @param Journal $journal
     * @param integer $limit
     * @return User[]
     */
    public function searchJournalUser($query, Journal $journal, $limit)
    {
        $query = $this->createQueryBuilder('u')
            ->select('PARTIAL u.{id,username,email,firstName,lastName}')
            ->join('u.journalUsers', 'ju')
            ->andWhere('ju.journal = :journal')
            ->andWhere('u.username LIKE :query OR u.email LIKE :query')
            ->andWhere('u.enabled = :enabled')
            ->setParameter('journal', $journal)
            ->setParameter('query', '%'.$query.'%')
            ->setParameter('enabled', true)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
    /**
     * @param  string                      $username
     * @return \Ojs\UserBundle\Entity\User
     */
    public function loadUserByUsername($username)
    {
        try {
            $q = $this
                ->createQueryBuilder('u')
                ->select('u')
                ->where('u.username = :username OR u.email = :email')
                ->setParameters(
                    [
                        'username' => $username,
                        'email' => $username,
                    ]
                )
                ->getQuery();
            $user = $q->getSingleResult();

            return $user;
        } catch (\Exception $e) {
            $message = sprintf(
                'Unable to find an active admin OjsUserBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }
    }

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
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * @param  User    $user
     * @param  Role    $role
     * @param  Journal $journal
     * @return bool
     */
    public function hasJournalRole(User $user, Role $role, Journal $journal)
    {
        $result = false;
        $journalUser = $this
            ->getEntityManager()
            ->getRepository('OjsJournalBundle:JournalUser')
            ->findOneBy(['user' => $user, 'journal' => $journal]);
        if (!$journalUser) {
            $result = $journalUser->getRoles()->contains($role);
        }

        return $result;
    }

    /**
     * Return user count by condition
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getCountBy($field, $value)
    {
        $qb = $this->createQueryBuilder("u");
        $qb->select("count(u.id)")
            ->where(
                $qb->expr()->eq("u.$field", ':value')
            )
            ->setParameter("value", $value)
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }


    /**
     * @param array   $roles
     * @param Journal $journal
     * @return User[]
     */
    public function findUsersByJournalRole(array $roles, Journal $journal = null)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->join('u.journalUsers', 'ju')
            ->join('ju.roles', 'r')
            ->andWhere('r.role IN (:roles)')
        ;

        if ($journal) {
            $queryBuilder
                ->andWhere('ju.journal = :journal')
                ->setParameter('journal', $journal)
            ;
        }
        $queryBuilder = $queryBuilder->setParameter('roles', $roles);
        $query = $queryBuilder
            ->distinct()
            ->getQuery()
        ;

        return $query->execute();
    }
}
