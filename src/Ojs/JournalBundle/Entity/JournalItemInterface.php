<?php

namespace Ojs\JournalBundle\Entity;

interface JournalItemInterface
{
    public function getId();

    /**
     * @return Journal
     */
    public function getJournal();

    /**
     * @param Journal $journal
     * @return Journal
     */
    public function setJournal(Journal $journal);
}
