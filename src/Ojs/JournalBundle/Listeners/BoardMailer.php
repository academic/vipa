<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\Board;
use Ojs\JournalBundle\Event\Board\BoardEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\UserBundle\Entity\User;

class BoardMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BoardEvents::POST_CREATE => 'onBoardPostCreate',
            BoardEvents::POST_UPDATE => 'onBoardPostUpdate',
            BoardEvents::PRE_DELETE => 'onBoardPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onBoardPostCreate(JournalItemEvent $itemEvent)
    {
        /** @var Board $item */
        $item = $itemEvent->getItem();
        $getMailEvent = $this->ojsMailer->getEventByName(BoardEvents::POST_CREATE, null, $item->getJournal());
        if(!$getMailEvent){
            return;
        }
        $item->setCurrentLocale($this->ojsMailer->locale);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$item->getJournal(),
                'board'             => (string)$item,
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
    public function onBoardPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(BoardEvents::POST_UPDATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'board'             => (string)$itemEvent->getItem(),
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
    public function onBoardPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(BoardEvents::PRE_DELETE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'board'             => (string)$itemEvent->getItem(),
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
