<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\CoreBundle\Annotation\Display;

/**
 * JournalSubmissionFile
 * @GRID\Source(columns="id,title,locale,visible")
 */
class JournalSubmissionFile extends SubmissionFile
{
    /** @var  Journal */
    private $journal;

    /**
     * @var string
     * @Display\File(path="submissionfiles")
     */
    private $file;

    /**
     * Get journal
     *
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journal
     *
     * @param  Journal $journal
     * @return SubmissionFile
     */
    public function setJournal(Journal $journal = null)
    {
        $this->journal = $journal;

        return $this;
    }
}
