<?php

namespace Ojstr\Common\Entity;

use Gedmo\Translatable\Translatable;

/**
 * Some common properties and functions for JournalBundle Entities
 */
class GenericExtendedEntity implements Translatable {

    /**
     * Translateable locale field
     */
    protected $locale;

    /**
     * @var datetime $created 
     */
    protected $created;

    /**
     * @var datetime $updated
     */
    protected $updated;

    /**
     * @var datetime $contentChanged
     */
    protected $contentChanged;

    /**
     * @var datetime
     */
    protected $deletedAt;

    public function setTranslatableLocale($locale) {
        $this->locale = $locale;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function getContentChanged() {
        return $this->contentChanged;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

}
