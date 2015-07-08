<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\UserBundle\Entity\User;
use Doctrine\Common\Collections\Collection;

/**
 * JournalUser
 */
class JournalUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Collection
     */
    private $journals;

    /**
     * @var Collection
     */
    private $users;

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
     * @return Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * @param Collection $journals
     */
    public function setJournals($journals)
    {
        $this->journals = $journals;
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
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

