<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\Section;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\Section\SectionEvents;

class SectionMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SectionEvents::POST_CREATE => 'onSectionPostCreate',
            SectionEvents::POST_UPDATE => 'onSectionPostUpdate',
            SectionEvents::PRE_DELETE  => 'onSectionPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onSectionPostCreate(JournalItemEvent $event)
    {
        $this->sendSectionMail($event, SectionEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onSectionPostUpdate(JournalItemEvent $event)
    {
        $this->sendSectionMail($event, SectionEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onSectionPreDelete(JournalItemEvent $event)
    {
        $this->sendSectionMail($event, SectionEvents::PRE_DELETE);
    }

    private function sendSectionMail(JournalItemEvent $event, string $name)
    {
        /** @var Section $section */
        $section = $event->getItem();
        $journal = $section->getJournal();
        $staff = $this->ojsMailer->getJournalStaff();
        $this->ojsMailer->sendEventMail($name, $staff, ['section' => $section], $journal);
    }
}
