<?php

namespace Ojs\AnalyticsBundle\Entity;

use Ojs\AnalyticsBundle\Traits\DownloadableTrait;
use Ojs\JournalBundle\Entity\IssueFile;

/**
 * IssueFileStatistic
 */
class IssueFileStatistic extends Statistic
{
    use DownloadableTrait;

    /**
     * @var IssueFile
     */
    private $issueFile;

    /**
     * @return IssueFile
     */
    public function getIssueFile()
    {
        return $this->issueFile;
    }

    /**
     * @param IssueFile $issue
     */
    public function setIssueFile($issue)
    {
        $this->issueFile = $issue;
    }
}

