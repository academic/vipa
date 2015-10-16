<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CmsBundle\Entity\File;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Ojs\CoreBundle\Annotation\Display;

/**
 * JournalFile
 */
class JournalFile extends File
{
    /** @var Journal */
    private $journal;

    /**
     * @Display\File(path="files")
     */
    private $path;

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

