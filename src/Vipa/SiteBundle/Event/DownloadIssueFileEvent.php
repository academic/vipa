<?php

namespace Vipa\SiteBundle\Event;

use Vipa\JournalBundle\Entity\IssueFile;
use Symfony\Component\EventDispatcher\Event;

class DownloadIssueFileEvent extends Event
{
    /**
     * @var IssueFile
     */
    protected $issueFile;

    /**
     * DownloadIssueFileEvent constructor.
     * @param IssueFile $issueFile
     */
    public function __construct($issueFile)
    {
        $this->issueFile = $issueFile;
    }

    /**
     * @return IssueFile
     */
    public function getIssueFile()
    {
        return $this->issueFile;
    }
}