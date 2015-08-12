<?php

namespace Ojs\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * Post
 * @GRID\Source(columns="id ,title , status, post_type")
 */
abstract class Post
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     * @GRID\Column(title="status")
     */
    private $status;

    /**
     * @var string
     * @GRID\Column(title="Post Type")
     */
    private $post_type;

    /**
     * @var string
     */
    private $object;

    /**
     * @var integer
     */
    private $objectId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Post
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
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
     * Set status
     *
     * @param integer $status
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set post_type
     *
     * @param string $postType
     * @return Post
     */
    public function setPostType($postType)
    {
        $this->post_type = $postType;

        return $this;
    }

    /**
     * Get post_type
     *
     * @return string
     */
    public function getPostType()
    {
        return $this->post_type;
    }

    /**
     * Set object
     *
     * @param string $object
     * @return Post
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     * @return Post
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add posts
     *
     * @param \Ojs\CmsBundle\Entity\Post $posts
     * @return Post
     */
    public function addPost(\Ojs\CmsBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Ojs\CmsBundle\Entity\Post $posts
     */
    public function removePost(\Ojs\CmsBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @var string
     */
    private $unique_key;

    /**
     * Set unique_key
     *
     * @param string $uniqueKey
     * @return Post
     */
    public function setUniqueKey($uniqueKey)
    {
        $this->unique_key = $uniqueKey;

        return $this;
    }

    /**
     * Get unique_key
     *
     * @return string
     */
    public function getUniqueKey()
    {
        return $this->getObject() . $this->getObjectId();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $translations;


    /**
     * Add translations
     *
     * @param \Ojs\CmsBundle\Entity\PostTranslation $translations
     * @return Post
     */
    public function addTranslation(\Ojs\CmsBundle\Entity\PostTranslation $translations)
    {
        $this->translations[] = $translations;

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Ojs\CmsBundle\Entity\PostTranslation $translations
     */
    public function removeTranslation(\Ojs\CmsBundle\Entity\PostTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
