<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CmsBundle\Entity\Page;

/**
 * JournalPage
 */
class JournalPage extends Page
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

