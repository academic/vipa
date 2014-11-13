<?php

namespace Ojs\Common\Entity;

use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Some common properties and functions for JournalBundle Entities
 *
 * @ExclusionPolicy("all")
 */
class GenericExtendedEntity implements Translatable
{

    /**
     * Translateable locale field
     */
    protected $locale;

    /**
     * @var Datetime $created
     */
    protected $created;

    /**
     * @var Datetime $updated
     */
    protected $updated;

    /**
     * @var Datetime $contentChanged
     */
    protected $contentChanged;

    /**
     * @var Datetime
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
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }
}
