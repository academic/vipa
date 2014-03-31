<?php

namespace Ojstr\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserJournalRole
 */
class UserJournalRole {

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

    public function __construct() {
        $this->user = new \Ojstr\UserBundle\Entity\User();
        $this->role = new \Ojstr\UserBundle\Entity\Role();
        $this->journal = new \Ojstr\JournalBundle\Entity\Journal();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserJournalRole
     */
    public function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set journalId
     *
     * @param integer $journalId
     * @return UserJournalRole
     */
    public function setJournalId($journalId) {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer 
     */
    public function getJournalId() {
        return $this->journalId;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return UserJournalRole
     */
    public function setRoleId($roleId) {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * @var \Ojstr\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Ojstr\JournalBundle\Entity\Journal
     */
    private $journal;

    /**
     * @var \Ojstr\UserBundle\Entity\Role
     */
    private $role;

    /**
     * Set user
     *
     * @param \Ojstr\UserBundle\Entity\User $user
     * @return UserJournalRole
     */
    public function setUser(\Ojstr\UserBundle\Entity\User $user = null) {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Ojstr\UserBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set journal
     *
     * @param \Ojstr\UserBundle\Entity\Journal $journal
     * @return UserJournalRole
     */
    public function setJournal(\Ojstr\UserBundle\Entity\Journal $journal = null) {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return \Ojstr\UserBundle\Entity\Journal 
     */
    public function getJournal() {
        return $this->journal;
    }

    /**
     * Set role
     *
     * @param \Ojstr\UserBundle\Entity\Role $role
     * @return UserJournalRole
     */
    public function setRole(\Ojstr\UserBundle\Entity\Role $role = null) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Ojstr\UserBundle\Entity\Role 
     */
    public function getRole() {
        return $this->role;
    }

}
