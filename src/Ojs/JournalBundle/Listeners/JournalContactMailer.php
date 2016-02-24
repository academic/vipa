<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalContact\JournalContactEvents;
use Ojs\UserBundle\Entity\User;

class JournalContactMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalContactEvents::POST_CREATE => 'onJournalContactPostCreate',
            JournalContactEvents::POST_UPDATE => 'onJournalContactPostUpdate',
            JournalContactEvents::PRE_DELETE => 'onJournalContactPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalContactPostCreate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalContactEvents::POST_CREATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'contact'           => (string)$itemEvent->getItem(),
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
    public function onJournalContactPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalContactEvents::POST_UPDATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'contact'           => (string)$itemEvent->getItem(),
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
    public function onJournalContactPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalContactEvents::PRE_DELETE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'contact'           => (string)$itemEvent->getItem(),
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
