<?php

namespace Ojs\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
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
     * @param $id string
     * @param $provider string
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByOauthId($id, $provider)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->join('OjsUserBundle:UserOauthAccount', 'oa', 'WITH', 'oa.user_id=u.id')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('oa.provider', ':provider'),
                    $qb->expr()->eq('oa.provider_user_id', ':user_id')
                )
            )
            ->setParameters(
                [
                    'provider' => $provider,
                    'user_id' => $id,
                ]
            );
        $result = $qb->getQuery()->getOneOrNullResult();

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
}
