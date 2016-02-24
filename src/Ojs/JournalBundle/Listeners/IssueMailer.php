<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\Issue\IssueEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\UserBundle\Entity\User;

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
        $getMailEvent = $this->ojsMailer->getEventByName(IssueEvents::POST_CREATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'issue'             => (string)$itemEvent->getItem(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onIssuePostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(IssueEvents::POST_UPDATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'issue'             => (string)$itemEvent->getItem(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onIssuePreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(IssueEvents::PRE_DELETE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'issue'             => (string)$itemEvent->getItem(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }
}
