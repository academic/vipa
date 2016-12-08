<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalIndex\JournalIndexEvents;
use Ojs\UserBundle\Entity\User;

class JournalIndexMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalIndexEvents::POST_CREATE => 'onJournalIndexPostCreate',
            JournalIndexEvents::POST_UPDATE => 'onJournalIndexPostUpdate',
            JournalIndexEvents::PRE_DELETE => 'onJournalIndexPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalIndexPostCreate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalIndexEvents::POST_CREATE.'.to.users', null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            goto sendmailtoadmins;
        }
        $mailUsers = $this->ojsMailer->getJournalRelatedUsers();
        /** @var User $user */
        foreach ($mailUsers as $user) {
            $transformParams = [
                'index'             => (string)$itemEvent->getItem(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }



        sendmailtoadmins:
        
        $getMailEvent = $this->ojsMailer->getEventByName(JournalIndexEvents::POST_CREATE.'.to.admins', null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        $mailUsers =  $this->ojsMailer->getAdminUsers();
        /** @var User $user */
        foreach ($mailUsers as $user) {
            $transformParams = [
                'index'             => (string)$itemEvent->getItem(),
                'done.by'           => $this->ojsMailer->currentUser()->getUsername(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'journal.edit'      => $this->router->generate('ojs_admin_journal_edit', ['id' => $itemEvent->getItem()->getJournal()->getId()])
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
    public function onJournalIndexPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalIndexEvents::POST_UPDATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'index'             => (string)$itemEvent->getItem(),
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
    public function onJournalIndexPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalIndexEvents::PRE_DELETE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'index'             => (string)$itemEvent->getItem(),
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
