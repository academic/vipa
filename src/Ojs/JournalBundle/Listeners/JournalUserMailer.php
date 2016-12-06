<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalUser\JournalUserEvents;
use Ojs\UserBundle\Entity\User;

class JournalUserMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalUserEvents::POST_CREATE => 'onJournalUserPostCreate',
            JournalUserEvents::POST_UPDATE => 'onJournalUserPostUpdate',
            JournalUserEvents::PRE_DELETE => 'onJournalUserPreDelete',
            JournalUserEvents::POST_ADD_JOURNAL => 'onJournalUserPostAddJournal',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalUserPostCreate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(JournalUserEvents::POST_CREATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'journal.user'      => (string)$itemEvent->getItem(),
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
    public function onJournalUserPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(JournalUserEvents::POST_UPDATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'journal.user'      => (string)$itemEvent->getItem(),
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
    public function onJournalUserPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(JournalUserEvents::PRE_DELETE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'journal.user'      => (string)$itemEvent->getItem(),
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
    public function onJournalUserPostAddJournal(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(JournalUserEvents::POST_ADD_JOURNAL, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'journal.user'      => (string)$itemEvent->getItem(),
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
