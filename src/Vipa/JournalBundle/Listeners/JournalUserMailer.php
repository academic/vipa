<?php

namespace Vipa\JournalBundle\Listeners;

use Vipa\JournalBundle\Entity\JournalUser;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\JournalUser\JournalUserEvents;

class JournalUserMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalUserEvents::POST_CREATE      => 'onJournalUserPostCreate',
            JournalUserEvents::POST_UPDATE      => 'onJournalUserPostUpdate',
            JournalUserEvents::PRE_DELETE       => 'onJournalUserPreDelete',
            JournalUserEvents::POST_ADD_JOURNAL => 'onJournalUserPostAddJournal',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalUserPostCreate(JournalItemEvent $event)
    {
        $this->sendUserMail($event, JournalUserEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalUserPostUpdate(JournalItemEvent $event)
    {
        $this->sendUserMail($event, JournalUserEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalUserPreDelete(JournalItemEvent $event)
    {
        $this->sendUserMail($event, JournalUserEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalUserPostAddJournal(JournalItemEvent $event)
    {
        $this->sendUserMail($event, JournalUserEvents::POST_ADD_JOURNAL);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     */
    private function sendUserMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalUser $user */
        $user = $event->getItem();
        $journal = $user->getJournal();
        $staff = $this->mailer->getJournalStaff();

        $params = [
            'journal'      => (string) $journal,
            'journal.user' => (string) $user,
        ];

        $this->mailer->sendEventMail($name, $staff, $params, $journal);
    }
}
