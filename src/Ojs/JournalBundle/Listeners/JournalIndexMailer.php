<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalIndex\JournalIndexEvents;

class JournalIndexMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalIndexEvents::POST_CREATE => 'onJournalIndexPostCreate',
            JournalIndexEvents::POST_UPDATE => 'onJournalIndexPostUpdate',
            JournalIndexEvents::PRE_DELETE => 'onJournalIndexPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalIndexPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Index', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalIndexPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Index', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalIndexPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Index', 'Deleted');
    }
}
