<?php

namespace Ojs\AnalyticsBundle\Entity;

use Ojs\AnalyticsBundle\Traits\DownloadableTrait;
use Ojs\JournalBundle\Entity\Issue;

/**
 * IssueFileStatistic
 */
class IssueFileStatistic extends Statistic
{
    use DownloadableTrait;

    /**
     * @var Issue
     */
    private $issueFile;

    /**
     * @return Issue
     */
    public function getIssueFile()
    {
        return $this->issueFile;
    }

    /**
     * @param Issue $issue
     */
    public function setIssueFile($issue)
    {
        $this->issueFile = $issue;
    }
}

