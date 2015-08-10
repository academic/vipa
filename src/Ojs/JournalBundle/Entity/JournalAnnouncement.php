<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CmsBundle\Entity\Announcement;
use APY\DataGridBundle\Grid\Mapping\Source;

/**
 * JournalAnnouncement
 * @Source(columns="id, title, content")
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

