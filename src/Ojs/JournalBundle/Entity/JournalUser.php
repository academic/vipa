<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\UserBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * JournalUser
 * @GRID\Source(columns="id, user.username, journal.title")
 */
class JournalUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Journal
     * @Grid\Column(field="journal.title", title="journal")
     */
    private $journal;

    /**
     * @var User
     * @Grid\Column(field="user.username", title="user")
     */
    private $user;

    /**
     * @var Collection
     */
    private $roles;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Collection $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}

