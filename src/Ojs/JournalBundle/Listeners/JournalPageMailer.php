<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalPage\JournalPageEvents;

class JournalPageMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalPageEvents::POST_CREATE => 'onJournalPagePostCreate',
            JournalPageEvents::POST_UPDATE => 'onJournalPagePostUpdate',
            JournalPageEvents::PRE_DELETE => 'onJournalPagePreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPagePostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Page', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPagePostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Page', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPagePreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Page', 'Deleted');
    }
}
