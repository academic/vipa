<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CmsBundle\Entity\Post;

/**
 * JournalPost
 */
class JournalPost extends Post
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

