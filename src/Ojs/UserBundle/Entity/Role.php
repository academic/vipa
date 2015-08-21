<?php

namespace Ojs\UserBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Role
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id, name, role")
 */
class Role
{
    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="role.id")
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="role.name")
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="role.role")
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

    public function __toString()
    {
        return $this->getRole();
    }
}
