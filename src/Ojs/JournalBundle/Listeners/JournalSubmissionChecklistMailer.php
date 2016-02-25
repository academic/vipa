<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalSubmissionChecklist\JournalSubmissionChecklistEvents;
use Ojs\UserBundle\Entity\User;

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
        $getMailEvent = $this->ojsMailer->getEventByName(JournalSubmissionChecklistEvents::POST_CREATE, null, $itemEvent->getItem()->getJournal());
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'submission.checklist'  => (string)$itemEvent->getItem(),
                'done.by'               => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username'     => $user->getUsername(),
                'receiver.fullName'     => $user->getFullName(),
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
    public function onJournalSubmissionChecklistPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalSubmissionChecklistEvents::POST_UPDATE, null, $itemEvent->getItem()->getJournal());
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'submission.checklist'  => (string)$itemEvent->getItem(),
                'done.by'               => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username'     => $user->getUsername(),
                'receiver.fullName'     => $user->getFullName(),
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
    public function onJournalSubmissionChecklistPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalSubmissionChecklistEvents::PRE_DELETE, null, $itemEvent->getItem()->getJournal());
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'submission.checklist'  => (string)$itemEvent->getItem(),
                'done.by'               => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username'     => $user->getUsername(),
                'receiver.fullName'     => $user->getFullName(),
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
