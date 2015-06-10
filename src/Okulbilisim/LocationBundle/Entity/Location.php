<?php

namespace Okulbilisim\LocationBundle\Entity;


use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Location
 * @ExclusionPolicy("all")
 */
class Location
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
     * @var integer
     * @Expose
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $childrens;

    /**
     * @var \Okulbilisim\LocationBundle\Entity\Location
     */
    private $parent;

    /**
     * @var integer
     * @Expose
     */
    private $parent_id;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childrens = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param  integer  $type
     * @return Location
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Add childrens
     *
     * @param  \Okulbilisim\LocationBundle\Entity\Location $childrens
     * @return Location
     */
    public function addChildren(\Okulbilisim\LocationBundle\Entity\Location $childrens)
    {
        $this->childrens[] = $childrens;

        return $this;
    }

    /**
     * Remove childrens
     *
     * @param \Okulbilisim\LocationBundle\Entity\Location $childrens
     */
    public function removeChildren(\Okulbilisim\LocationBundle\Entity\Location $childrens)
    {
        $this->childrens->removeElement($childrens);
    }

    /**
     * Get childrens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * Get parent
     *
     * @return \Okulbilisim\LocationBundle\Entity\Location
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param  \Okulbilisim\LocationBundle\Entity\Location $parent
     * @return Location
     */
    public function setParent(\Okulbilisim\LocationBundle\Entity\Location $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent_id
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set parent_id
     *
     * @param  integer  $parentId
     * @return Location
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
     * @param  string   $name
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
