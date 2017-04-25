<?php

namespace Vipa\JournalBundle\Listeners;

use Vipa\JournalBundle\Entity\SubmissionChecklist;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\JournalSubmissionChecklist\JournalSubmissionChecklistEvents;

class JournalSubmissionChecklistMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalSubmissionChecklistEvents::POST_CREATE => 'onJournalSubmissionChecklistPostCreate',
            JournalSubmissionChecklistEvents::POST_UPDATE => 'onJournalSubmissionChecklistPostUpdate',
            JournalSubmissionChecklistEvents::PRE_DELETE  => 'onJournalSubmissionChecklistPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalSubmissionChecklistPostCreate(JournalItemEvent $event)
    {
        $this->sendChecklistMail($event, JournalSubmissionChecklistEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalSubmissionChecklistPostUpdate(JournalItemEvent $event)
    {
        $this->sendChecklistMail($event, JournalSubmissionChecklistEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalSubmissionChecklistPreDelete(JournalItemEvent $event)
    {
        $this->sendChecklistMail($event, JournalSubmissionChecklistEvents::PRE_DELETE);
    }

    private function sendChecklistMail(JournalItemEvent $event, string $name)
    {
        /** @var SubmissionChecklist $checklist */
        $checklist = $event->getItem();
        $journal = $checklist->getJournal();
        $staff = $this->mailer->getJournalStaff();
        $params = ['submission.checklist' => (string) $checklist];
        $this->mailer->sendEventMail($name, $staff, $params, $journal);
    }
}
