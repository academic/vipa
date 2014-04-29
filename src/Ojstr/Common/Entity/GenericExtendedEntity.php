<?php

namespace Ojstr\Common\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Some common properties and functions for JournalBundle Entities
 */
class GenericExtendedEntity {

    public function getCreated() {
        return $this->created;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function getContentChanged() {
        return $this->contentChanged;
    }

    public function getDeletedAt() {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

}
