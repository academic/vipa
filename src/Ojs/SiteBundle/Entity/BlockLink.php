<?php

namespace Ojs\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Okulbilisim\CmsBundle\OkulbilisimCmsBundle;

/**
 * BlockLink
 */
class BlockLink
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $block_id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \Ojs\SiteBundle\Entity\Block
     */
    private $block;

    /** @var  \Okulbilisim\CmsBundle\Entity\Post */
    private $post;
    /** @var  integer */
    private $post_id;
    /**
     * Set id
     *
     * @param integer $id
     * @return BlockLink
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set block_id
     *
     * @param integer $blockId
     * @return BlockLink
     */
    public function setBlockId($blockId)
    {
        $this->block_id = $blockId;

        return $this;
    }

    /**
     * Get block_id
     *
     * @return integer
     */
    public function getBlockId()
    {
        return $this->block_id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return BlockLink
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return BlockLink
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set block
     *
     * @param \Ojs\SiteBundle\Entity\Block $block
     * @return BlockLink
     */
    public function setBlock(\Ojs\SiteBundle\Entity\Block $block = null)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * Get block
     *
     * @return \Ojs\SiteBundle\Entity\Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @var integer
     */
    private $link_order;


    /**
     * Set link_order
     *
     * @param integer $linkOrder
     * @return BlockLink
     */
    public function setLinkOrder($linkOrder)
    {
        $this->link_order = $linkOrder;

        return $this;
    }

    /**
     * Get link_order
     *
     * @return integer
     */
    public function getLinkOrder()
    {
        return $this->link_order;
    }

    /**
     * @return \Okulbilisim\CmsBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param \Okulbilisim\CmsBundle\Entity\Post $post
     * @return $this
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     * @return $this
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
        return $this;
    }
    
}
