<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalIndex;
use Ojs\JournalBundle\Event\JournalIndex\JournalIndexEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class JournalIndexMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalIndexEvents::POST_CREATE => 'onJournalIndexPostCreate',
            JournalIndexEvents::POST_UPDATE => 'onJournalIndexPostUpdate',
            JournalIndexEvents::PRE_DELETE  => 'onJournalIndexPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalIndexPostCreate(JournalItemEvent $event)
    {
        $this->sendIndexMail($event, JournalIndexEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalIndexPostUpdate(JournalItemEvent $event)
    {
        $this->sendIndexMail($event, JournalIndexEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalIndexPreDelete(JournalItemEvent $event)
    {
        $this->sendIndexMail($event, JournalIndexEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     */
    private function sendIndexMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalIndex $index */
        $index = $event->getItem();
        $journal = $index->getJournal();
        $staff = $this->ojsMailer->getJournalStaff();

        $params = [
            'journal' => (string) $journal,
            'index'   => (string) $index,
        ];

        $this->ojsMailer->sendEventMail($name, $staff, $params, $journal);
    }
}
