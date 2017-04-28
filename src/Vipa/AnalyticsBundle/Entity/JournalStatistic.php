<?php

namespace Vipa\AnalyticsBundle\Entity;

use Vipa\AnalyticsBundle\Traits\ViewableTrait;
use Vipa\JournalBundle\Entity\Journal;

/**
 * JournalStatistic
 */
class JournalStatistic extends Statistic
{
    use ViewableTrait;

    /**
     * @var Journal
     */
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
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;
    }
}

