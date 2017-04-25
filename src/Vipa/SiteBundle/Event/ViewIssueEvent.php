<?php

namespace Vipa\SiteBundle\Event;

use Vipa\JournalBundle\Entity\Issue;
use Symfony\Component\EventDispatcher\Event;

class ViewIssueEvent extends Event
{
    /**
     * @var Issue
     */
    protected $issue;

    /**
     * ViewIssueEvent constructor.
     * @param Issue $issue
     */
    public function __construct($issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }
}