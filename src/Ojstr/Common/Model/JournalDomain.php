<?php

namespace Ojstr\Common\Model;

class JournalDomain {

    private $currentJournal;

    public function getCurrentJournal() {
        return $this->currentJournal;
    }

    public function setCurrentJournal($currentJournal) {
        $this->currentJournal = $currentJournal;
    }

}
