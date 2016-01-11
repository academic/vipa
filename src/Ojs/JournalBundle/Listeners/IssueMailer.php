<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\Issue\IssueEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class IssueMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            IssueEvents::POST_CREATE => 'onIssuePostCreate',
            IssueEvents::POST_UPDATE => 'onIssuePostUpdate',
            IssueEvents::PRE_DELETE => 'onIssuePreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onIssuePostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Issue', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onIssuePostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Issue', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onIssuePreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Issue', 'Deleted');
    }
}
