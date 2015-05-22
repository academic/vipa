<?php

namespace Ojs\Common\Entity;

/**
 * Class GenericEntityInterface
 * @package Ojs\Common\Entity
 */
trait GenericEntityTrait
{
    /**
     * Translateable locale field
     */
    protected $locale;

    /**
     * @var \DateTime $created
     */
    protected $created;

    /**
     * @var \DateTime $updated
     */
    protected $updated;

    /**
     * @var \DateTime $contentChanged
     */
    protected $contentChanged;

    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     *
     * @var String
     */
    protected $createdBy = "";

    /**
     * @var String
     */
    protected $updatedBy = "";

    protected $tags = '';

    public function __construct()
    {
        php_sapi_name() == 'cli' && $this->createdBy = "";
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getContentChanged()
    {
        return $this->contentChanged;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param  string $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}
