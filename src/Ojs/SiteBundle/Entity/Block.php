<?php

namespace Ojs\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Block
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
    private $objectType;

    /**
     * @var integer
     */
    private $objectId;

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
     * @var Collection|BlockLink[]
     */
    private $links;
    /**
     * @var string
     */
    private $color;
    /**
     * @var integer
     */
    private $block_order;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new ArrayCollection();
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
     * Get objectType
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Set objectType
     *
     * @param  string $objectType
     * @return Block
     */
    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set objectId
     *
     * @param  integer $objectId
     * @return Block
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

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
     * Set title
     *
     * @param  string $title
     * @return Block
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * Set type
     *
     * @param  string $type
     * @return Block
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * Set content
     *
     * @param  string $content
     * @return Block
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Add links
     *
     * @param  BlockLink $links
     * @return Block
     */
    public function addLink(BlockLink $links)
    {
        $this->links[] = $links;

        return $this;
    }

    /**
     * Remove links
     *
     * @param BlockLink $links
     */
    public function removeLink(BlockLink $links)
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
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set color
     *
     * @param  string $color
     * @return Block
     */
    public function setColor($color)
    {
        $this->color = $color;

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

    /**
     * Set block_order
     *
     * @param  integer $blockOrder
     * @return Block
     */
    public function setBlockOrder($blockOrder)
    {
        $this->block_order = $blockOrder;

        return $this;
    }
}
