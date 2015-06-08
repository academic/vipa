<?php

namespace Ojs\UserBundle\Entity;

use Ojs\JournalBundle\Entity\Journal;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * UserJournalRole
 * @GRID\Source(columns="id,user.username,user.email,journal.title,role.name")
 */
class UserJournalRole
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
     * @GRID\Column(field="journal.title",title="Journal",type="text",visible="false")
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
     * Set user
     *
     * @param  User            $user
     * @return UserJournalRole
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

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
     * Set journal
     *
     * @param  Journal         $journal
     * @return UserJournalRole
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

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
     * Set role
     *
     * @param  Role            $role
     * @return UserJournalRole
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

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
}
