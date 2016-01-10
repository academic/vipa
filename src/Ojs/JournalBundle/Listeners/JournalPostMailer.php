<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalPost\JournalPostEvents;

class JournalPostMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalPostEvents::POST_CREATE => 'onJournalPostPostCreate',
            JournalPostEvents::POST_UPDATE => 'onJournalPostPostUpdate',
            JournalPostEvents::PRE_DELETE => 'onJournalPostPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPostPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Post', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPostPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Post', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPostPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Post', 'Deleted');
    }
}
