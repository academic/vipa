<?php

namespace Ojs\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Block
 * @ORM\OrderBy({"block_order" = "ASC"})
 */
class Block
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $object_type;

    /**
     * @var integer
     */
    private $object_id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $links;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set object_type
     *
     * @param string $objectType
     * @return Block
     */
    public function setObjectType($objectType)
    {
        $this->object_type = $objectType;

        return $this;
    }

    /**
     * Get object_type
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * Set object_id
     *
     * @param integer $objectId
     * @return Block
     */
    public function setObjectId($objectId)
    {
        $this->object_id = $objectId;

        return $this;
    }

    /**
     * Get object_id
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Block
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Block
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Block
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add links
     *
     * @param \Ojs\SiteBundle\Entity\BlockLink $links
     * @return Block
     */
    public function addLink(\Ojs\SiteBundle\Entity\BlockLink $links)
    {
        $this->links[] = $links;

        return $this;
    }

    /**
     * Remove links
     *
     * @param \Ojs\SiteBundle\Entity\BlockLink $links
     */
    public function removeLink(\Ojs\SiteBundle\Entity\BlockLink $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }
    /**
     * @var string
     */
    private $color;


    /**
     * Set color
     *
     * @param string $color
     * @return Block
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
    /**
     * @var integer
     */
    private $block_order;


    /**
     * Set block_order
     *
     * @param integer $blockOrder
     * @return Block
     */
    public function setBlockOrder($blockOrder)
    {
        $this->block_order = $blockOrder;

        return $this;
    }

    /**
     * Get block_order
     *
     * @return integer
     */
    public function getBlockOrder()
    {
        return $this->block_order;
    }
}
