<?php

namespace Vipa\UserBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;

/**
 * Role
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id, name, role")
 */
class Role
{
    /**
     * @var integer
     * @GRID\Column(title="role.id")
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @JMS\Groups({"export"})
     * @GRID\Column(title="role.name")
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @JMS\Groups({"export"})
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    public function __toString()
    {
        return $this->getRole();
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
}
