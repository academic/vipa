<?php

namespace Ojs\SiteBundle\Event;

use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\EventDispatcher\Event;

class ViewJournalEvent extends Event
{
    /**
     * @var Journal
     */
    protected $journal;

    /**
     * ViewJournalEvent constructor.
     * @param Journal $journal
     */
    public function __construct($journal)
    {
        $this->journal = $journal;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }
}