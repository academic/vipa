<?php

namespace Ojstr\JournalBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

class TimestampableEntity {

    public function getCreated() {
        return $this->created;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function getContentChanged() {
        return $this->contentChanged;
    }

}
