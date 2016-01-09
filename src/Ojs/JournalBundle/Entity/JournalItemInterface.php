<?php

namespace Ojs\JournalBundle\Entity;

interface JournalItemInterface
{
    public function getId();

    public function getJournal();

    public function setJournal(Journal $journal);
}
