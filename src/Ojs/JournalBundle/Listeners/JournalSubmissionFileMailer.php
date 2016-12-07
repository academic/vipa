<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalSubmissionFile;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalSubmissionFile\JournalSubmissionFileEvents;

class JournalSubmissionFileMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalSubmissionFileEvents::POST_CREATE => 'onJournalSubmissionFilePostCreate',
            JournalSubmissionFileEvents::POST_UPDATE => 'onJournalSubmissionFilePostUpdate',
            JournalSubmissionFileEvents::PRE_DELETE  => 'onJournalSubmissionFilePreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalSubmissionFilePostCreate(JournalItemEvent $event)
    {
        $this->sendFileMail($event, JournalSubmissionFileEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalSubmissionFilePostUpdate(JournalItemEvent $event)
    {
        $this->sendFileMail($event, JournalSubmissionFileEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalSubmissionFilePreDelete(JournalItemEvent $event)
    {
        $this->sendFileMail($event, JournalSubmissionFileEvents::PRE_DELETE);
    }

    private function sendFileMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalSubmissionFile $file */
        $file = $event->getItem();
        $journal = $file->getJournal();
        $staff = $this->mailer->getJournalStaff();
        $params = ['submission.file' => (string) $file];
        $this->mailer->sendEventMail($name, $staff, $params, $journal);
    }
}
