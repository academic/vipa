<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalContact\JournalContactEvents;

class JournalContactMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalContactEvents::POST_CREATE => 'onJournalContactPostCreate',
            JournalContactEvents::POST_UPDATE => 'onJournalContactPostUpdate',
            JournalContactEvents::PRE_DELETE => 'onJournalContactPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalContactPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Contact', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalContactPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Contact', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalContactPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Contact', 'Deleted');
    }
}
