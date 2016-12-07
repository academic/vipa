<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Event\Issue\IssueEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class IssueMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            IssueEvents::POST_CREATE => 'onIssuePostCreate',
            IssueEvents::POST_UPDATE => 'onIssuePostUpdate',
            IssueEvents::PRE_DELETE  => 'onIssuePreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onIssuePostCreate(JournalItemEvent $event)
    {

        $this->sendIssueMail($event, IssueEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onIssuePostUpdate(JournalItemEvent $event)
    {

        $this->sendIssueMail($event, IssueEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onIssuePreDelete(JournalItemEvent $event)
    {

        $this->sendIssueMail($event, IssueEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     */
    private function sendIssueMail(JournalItemEvent $event, string $name)
    {
        /** @var Issue $issue */
        $issue = $event->getItem();
        $journal = $issue->getJournal();
        $staff = $this->mailer->getJournalStaff();

        $params = [
            'journal' => (string) $journal,
            'issue'   => (string) $issue,
        ];

        $this->mailer->sendEventMail($name, $staff, $params, $journal);
    }
}
