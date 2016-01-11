<?php

namespace Ojs\JournalBundle\Entity;

/**
 * @method getId
 */
interface JournalItemInterface
{
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
