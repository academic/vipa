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
     * @param IssueFile $issueFile
     */
    public function setIssueFile($issueFile)
    {
        $this->issueFile = $issueFile;
    }
}

