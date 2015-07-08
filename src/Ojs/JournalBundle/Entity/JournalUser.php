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
    private $journal;

    /**
     * @var Collection
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
     * @return Collection
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Collection $journal
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
    }

    /**
     * @return Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Collection $user
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

