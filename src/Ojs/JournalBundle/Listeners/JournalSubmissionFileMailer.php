<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalSubmissionFile\JournalSubmissionFileEvents;

class JournalSubmissionFileMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalSubmissionFileEvents::POST_CREATE => 'onJournalSubmissionFilePostCreate',
            JournalSubmissionFileEvents::POST_UPDATE => 'onJournalSubmissionFilePostUpdate',
            JournalSubmissionFileEvents::PRE_DELETE => 'onJournalSubmissionFilePreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalSubmissionFilePostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Submission File', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalSubmissionFilePostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Submission File', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalSubmissionFilePreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Journal Submission File', 'Deleted');
    }
}
