<?php

namespace Ojs\JournalBundle\Event;

use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Symfony\Component\EventDispatcher\Event;

class NewAnnouncementEvent extends Event
{
    /**
     * @var JournalAnnouncement
     */
    private $announcement;

    /**
     * NewAnnouncementEvent constructor.
     * @param JournalAnnouncement $announcement
     */
    public function __construct(JournalAnnouncement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * @return JournalAnnouncement
     */
    public function getAnnouncement()
    {
        return $this->announcement;
    }
}