<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CmsBundle\Entity\Announcement;
use APY\DataGridBundle\Grid\Mapping\Source;
use Ojs\CoreBundle\Annotation\Display;

/**
 * JournalAnnouncement
 * @Source(columns="id, title, content")
 */
class JournalAnnouncement extends Announcement
{
    /**
     * @var string
     * @Display\Image(filter="announcement_croped")
     */
    private $image;

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

