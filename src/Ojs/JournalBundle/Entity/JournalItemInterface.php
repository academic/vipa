<?php

namespace Ojs\JournalBundle\Entity;

interface JournalItemInterface
{
    public function getJournal();

    public function setJournal(Journal $journal);
}
