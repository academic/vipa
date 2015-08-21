<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CmsBundle\Entity\Post;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

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

