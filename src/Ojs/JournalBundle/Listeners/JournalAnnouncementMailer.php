<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Ojs\JournalBundle\Event\JournalAnnouncement\JournalAnnouncementEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class JournalAnnouncementMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalAnnouncementEvents::POST_CREATE => 'onJournalAnnouncementPostCreate',
            JournalAnnouncementEvents::POST_UPDATE => 'onJournalAnnouncementPostUpdate',
            JournalAnnouncementEvents::PRE_DELETE  => 'onJournalAnnouncementPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalAnnouncementPostCreate(JournalItemEvent $event)
    {
        $this->sendAnnouncementMail($event, JournalAnnouncementEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalAnnouncementPostUpdate(JournalItemEvent $event)
    {
        $this->sendAnnouncementMail($event, JournalAnnouncementEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalAnnouncementPreDelete(JournalItemEvent $event)
    {
        $this->sendAnnouncementMail($event, JournalAnnouncementEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     */
    private function sendAnnouncementMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalAnnouncement $announcement */
        $announcement = $event->getItem();
        $journal = $announcement->getJournal();
        $staff = $this->ojsMailer->getJournalStaff();

        $params = [
            'journal'      => (string) $journal,
            'announcement' => (string) $announcement,
        ];

        $this->ojsMailer->sendEventMail($name, $staff, $params, $journal);
    }
}
