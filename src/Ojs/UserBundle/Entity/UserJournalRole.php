<?php

namespace Ojs\UserBundle\Entity;

/**
 * UserJournalRole
 */
class UserJournalRole
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var integer
     */
    private $roleId;

    public function __construct()
    {
        $this->user = new \Ojs\UserBundle\Entity\User();
        $this->role = new \Ojs\UserBundle\Entity\Role();
        $this->journal = new \Ojs\JournalBundle\Entity\Journal();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param  integer         $userId
     * @return UserJournalRole
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set journalId
     *
     * @param  integer         $journalId
     * @return UserJournalRole
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set roleId
     *
     * @param  integer         $roleId
     * @return UserJournalRole
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @var \Ojs\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Ojs\JournalBundle\Entity\Journal
     */
    private $journal;

    /**
     * @var \Ojs\UserBundle\Entity\Role
     */
    private $role;

    /**
     * Set user
     *
     * @param  \Ojs\UserBundle\Entity\User $user
     * @return UserJournalRole
     */
    public function setUser(\Ojs\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ojs\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set journal
     *
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return UserJournalRole
     */
    public function setJournal(\Ojs\JournalBundle\Entity\Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return \Ojstr\Journalundle\Entity\Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set role
     *
     * @param  \Ojs\UserBundle\Entity\Role $role
     * @return UserJournalRole
     */
    public function setRole(\Ojs\UserBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Ojs\UserBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

}
