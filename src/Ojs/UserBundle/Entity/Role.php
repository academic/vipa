<?php

namespace Ojs\UserBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Role
 * @ExclusionPolicy("all")
 */
class Role implements RoleInterface
{
    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Expose
     */
    private $name;

    /**
     * @var string
     * @Expose
     */
    private $role;

    /**
     * @var boolean
     * @Expose
     */
    private $isSystemRole;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * Set name
     *
     * @param  string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param  boolean                       $isSystemRole
     * @return \Ojs\UserBundle\Entity\Role
     */
    public function setIsSystemRole($isSystemRole)
    {
        $this->isSystemRole = $isSystemRole;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getIsSystemRole()
    {
        return $this->isSystemRole;
    }

    /**
     * Set role
     *
     * @param  string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add users
     *
     * @param  \Ojs\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Ojs\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Ojs\UserBundle\Entity\User $users
     */
    public function removeUser(\Ojs\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->getRole();
    }
}
