<?php

namespace Okulbilisim\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 */
class Location
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
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
     * Set name
     *
     * @param string $name
     * @return Location
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
     * Set type
     *
     * @param integer $type
     * @return Location
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
     * Add childrens
     *
     * @param \Okulbilisim\LocationBundle\Entity\Location $childrens
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
     * Set parent
     *
     * @param \Okulbilisim\LocationBundle\Entity\Location $parent
     * @return Location
     */
    public function setParent(\Okulbilisim\LocationBundle\Entity\Location $parent = null)
    {
        $this->parent = $parent;

        return $this;
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
     * @var integer
     */
    private $parent_id;


    /**
     * Set parent_id
     *
     * @param integer $parentId
     * @return Location
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

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
}
