<?php

namespace Vipa\AnalyticsBundle\Entity;

use Vipa\AnalyticsBundle\Traits\ViewableTrait;
use Vipa\JournalBundle\Entity\Issue;

/**
 * IssueStatistic
 */
class IssueStatistic extends Statistic
{
    use ViewableTrait;

    /**
     * @var Issue
     */
    private $issue;

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }
}

