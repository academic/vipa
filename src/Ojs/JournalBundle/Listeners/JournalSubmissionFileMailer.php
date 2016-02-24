<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalSubmissionFile\JournalSubmissionFileEvents;
use Ojs\UserBundle\Entity\User;

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
        $getMailEvent = $this->ojsMailer->getEventByName(JournalSubmissionFileEvents::POST_CREATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'submission.file'       => (string)$itemEvent->getItem(),
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
    public function onJournalSubmissionFilePostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalSubmissionFileEvents::POST_UPDATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'submission.file'       => (string)$itemEvent->getItem(),
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
    public function onJournalSubmissionFilePreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalSubmissionFileEvents::PRE_DELETE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'submission.file'       => (string)$itemEvent->getItem(),
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
