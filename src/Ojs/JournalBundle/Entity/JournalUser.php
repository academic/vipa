<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * JournalUser
 * @GRID\Source(columns="id, user.email")
 */
class JournalUser implements JournalItemInterface
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var Journal
     */
    private $journal;
    /**
     * @var User
     * @Grid\Column(field="user.email", title="user")
     */
    private $user;
    /**
     * @var Collection
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return $this
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
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
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
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
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function removeRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getUser();
    }
}
