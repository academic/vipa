<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalEvents;

class JournalMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [JournalEvents::POST_UPDATE => 'onJournalPostUpdate'];
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalPostUpdate(JournalEvent $event)
    {
        $this->ojsMailer->sendEventMail(
            JournalEvents::POST_UPDATE,
            $this->ojsMailer->getJournalStaff(),
            ['journal' => (string) $event->getJournal()],
            $event->getJournal()
        );
    }
}
