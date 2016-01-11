<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalSubmissionChecklist\JournalSubmissionChecklistEvents;

class JournalSubmissionChecklistMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalSubmissionChecklistEvents::POST_CREATE => 'onJournalSubmissionChecklistPostCreate',
            JournalSubmissionChecklistEvents::POST_UPDATE => 'onJournalSubmissionChecklistPostUpdate',
            JournalSubmissionChecklistEvents::PRE_DELETE => 'onJournalSubmissionChecklistPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalSubmissionChecklistPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Submission Checklist', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalSubmissionChecklistPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Submission Checklist', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalSubmissionChecklistPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Submission Checklist', 'Deleted');
    }
}
