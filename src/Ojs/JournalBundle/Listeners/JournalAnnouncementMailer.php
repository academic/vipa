<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalAnnouncement\JournalAnnouncementEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\UserBundle\Entity\User;

class JournalAnnouncementMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalAnnouncementEvents::POST_CREATE => 'onJournalAnnouncementPostCreate',
            JournalAnnouncementEvents::POST_UPDATE => 'onJournalAnnouncementPostUpdate',
            JournalAnnouncementEvents::PRE_DELETE => 'onJournalAnnouncementPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalAnnouncementPostCreate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalAnnouncementEvents::POST_CREATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'announcement'      => (string)$itemEvent->getItem(),
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
    public function onJournalAnnouncementPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalAnnouncementEvents::POST_UPDATE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'announcement'      => (string)$itemEvent->getItem(),
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
    public function onJournalAnnouncementPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(JournalAnnouncementEvents::PRE_DELETE);
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'announcement'      => (string)$itemEvent->getItem(),
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
