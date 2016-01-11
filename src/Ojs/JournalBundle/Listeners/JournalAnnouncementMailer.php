<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalAnnouncement\JournalAnnouncementEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class JournalAnnouncementMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalAnnouncementEvents::POST_CREATE => 'onJournalAnnouncementPostCreate',
            JournalAnnouncementEvents::POST_UPDATE => 'onJournalAnnouncementPostUpdate',
            JournalAnnouncementEvents::PRE_DELETE => 'onJournalAnnouncementPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalAnnouncementPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Announcement', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalAnnouncementPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Announcement', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalAnnouncementPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Announcement', 'Deleted');
    }
}
