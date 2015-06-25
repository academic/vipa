<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\Role;

/**
 * UserJournalRole
 * @GRID\Source(columns="id,user.username,user.email,role.name")
 */
class JournalRole
{
    /**
     * @var integer
     * @GRID\Column(title="user.journalrole.user.id")
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

    /**
     * @var User
     * @GRID\Column(title="user.journalrole.user.username",field="user.username")
     * @GRID\Column(title="user.journalrole.email",field="user.email")
     */
    private $user;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var Role
     * @GRID\Column(field="role.name",title="user.journalrole.role")
     */
    private $role;

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
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId
     *
     * @param  integer         $userId
     * @return JournalRole
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

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
     * Set journalId
     *
     * @param  integer         $journalId
     * @return JournalRole
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

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
     * Set roleId
     *
     * @param  integer         $roleId
     * @return JournalRole
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User            $user
     * @return JournalRole
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journal
     *
     * @param  Journal         $journal
     * @return JournalRole
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param  Role            $role
     * @return JournalRole
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

        return $this;
    }
}