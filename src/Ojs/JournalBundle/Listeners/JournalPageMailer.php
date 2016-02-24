<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalPage\JournalPageEvents;
use Ojs\UserBundle\Entity\User;

class JournalPageMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalPageEvents::POST_CREATE => 'onJournalPagePostCreate',
            JournalPageEvents::POST_UPDATE => 'onJournalPagePostUpdate',
            JournalPageEvents::PRE_DELETE => 'onJournalPagePreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalPagePostCreate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalPageEvents::POST_CREATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'page'              => (string)$itemEvent->getItem(),
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
    public function onJournalPagePostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalPageEvents::POST_UPDATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'page'              => (string)$itemEvent->getItem(),
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
    public function onJournalPagePreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalPageEvents::PRE_DELETE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'page'              => (string)$itemEvent->getItem(),
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
