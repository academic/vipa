<?php

namespace Vipa\JournalBundle\Listeners;

use Vipa\JournalBundle\Entity\Section;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\Section\SectionEvents;

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
        $staff = $this->mailer->getJournalStaff();
        $this->mailer->sendEventMail($name, $staff, ['section' => $section], $journal);
    }
}
