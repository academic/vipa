<?php

namespace Ojs\JournalBundle\Entity;
use Ojs\CmsBundle\Entity\Announcement;

/**
 * JournalAnnouncement
 */
class JournalAnnouncement extends Announcement
{
    /** @var Journal */
    private $journal;

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
    }
}

