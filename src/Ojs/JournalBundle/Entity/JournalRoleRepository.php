<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ojs\UserBundle\Entity\User;

class JournalRoleRepository extends EntityRepository
{
    /**
     * get user list of users a journal with journal_id
     * @param $journal_id
     * @param  bool       $grouppedByRole
     * @return array|bool
     */
    public function getUsers($journal_id, $grouppedByRole = false)
    {
        /** @var JournalRole[] $user_journal_roles */
        $user_journal_roles = $this->getEntityManager()->getRepository('OjsUserBundle:JournalRole')->findBy(
            array('journalId' => $journal_id)
        );
        $entities = array();
        if (!is_array($user_journal_roles)) {
            return false;
        }
        foreach ($user_journal_roles as $item) {
            $entities[] = $item->getUser();
        }

        if (!$grouppedByRole) {
            return $entities;
        }

        $users = [];
        foreach ($entities as $user) {
            /** @var User $user */
            foreach ($user->getRoles() as $role) {
                /** @var Role $role */
                $users[$role->getName()][] = $user;
            }
        }

        return $users;
    }

    /**
     * @param $user_id
     * @param  bool  $onlyJournalIds
     * @return array
     */
    public function userJournalsWithRoles($user_id, $onlyJournalIds = false)
    {
        /** @var JournalRole[] $data */
        $data = $this->getEntityManager()->createQuery(
            'SELECT u FROM OjsJournalBundle:JournalRole u WHERE u.userId = :user_id '
        )->setParameter('user_id', $user_id)->getResult();
        $entities = array();
        if ($data) {
            foreach ($data as $item) {
                $entities[$item->getJournalId()]['journal'] = $onlyJournalIds ? $item->getJournal()->getId(
                ) : $item->getJournal();
                $entities[$item->getJournalId()]['roles'][] = $item->getRole();
            }
        }

        return $entities;
    }

    /**
     * @param  string|array      $role
     * @return JournalRole[]
     */
    public function findAllByJournalRole($role)
    {
        $query = $this->createQueryBuilder('ujr')
            ->select('ujr')
            ->join('ujr.user', 'u')
            ->join('ujr.role', 'r')
            ->join('ujr.journal', 'j');
        if (is_array($role)) {
            $query = $query->where('r.role IN (:role)');
        } else {
            $query = $query->where('r.role = :role');
        }
        $query = $query
            ->setParameter('role', $role)
            ->getQuery();

        return $query;
    }

    /**
     * @param  Journal $journal
     * @param  User    $user
     * @return array
     */
    public function findAllByJournalAndUser(Journal $journal, User $user)
    {
        /** @var JournalRole[] $query */
        $query = $this->createQueryBuilder('ujr')
            ->andWhere('ujr.user = :user')
            ->andWhere('ujr.journal = :journal')
            ->setParameter('journal', $journal)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        $roles = array();
        if ($query) {
            foreach ($query as $userJournalRole) {
                $roles[] = (string) $userJournalRole->getRole();
            }
        }

        return $roles;
    }
}
