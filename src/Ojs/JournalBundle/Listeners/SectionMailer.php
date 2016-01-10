<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\Section\SectionEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class SectionMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            SectionEvents::POST_CREATE => 'onSectionPostCreate',
            SectionEvents::POST_UPDATE => 'onSectionPostUpdate',
            SectionEvents::PRE_DELETE => 'onSectionPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onSectionPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Section', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onSectionPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Section', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onSectionPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Section', 'Deleted');
    }
}
