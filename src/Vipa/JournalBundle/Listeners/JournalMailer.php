<?php

namespace Vipa\JournalBundle\Listeners;

use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalEvents;

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
        $this->mailer->sendEventMail(
            JournalEvents::POST_UPDATE,
            $this->mailer->getJournalStaff(),
            ['journal' => (string) $event->getJournal()],
            $event->getJournal()
        );
    }
}
