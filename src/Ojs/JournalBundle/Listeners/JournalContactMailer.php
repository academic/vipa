<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalContact;
use Ojs\JournalBundle\Event\JournalContact\JournalContactEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class JournalContactMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalContactEvents::POST_CREATE => 'onJournalContactPostCreate',
            JournalContactEvents::POST_UPDATE => 'onJournalContactPostUpdate',
            JournalContactEvents::PRE_DELETE  => 'onJournalContactPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalContactPostCreate(JournalItemEvent $event)
    {
        $this->sendContactMail($event, JournalContactEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalContactPostUpdate(JournalItemEvent $event)
    {
        $this->sendContactMail($event, JournalContactEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalContactPreDelete(JournalItemEvent $event)
    {
        $this->sendContactMail($event, JournalContactEvents::PRE_DELETE);
    }

    private function sendContactMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalContact */
        $contact = $event->getItem();
        $journal = $contact->getJournal();
        $staff = $this->ojsMailer->getJournalStaff();
        $params = ['contact' => (string) $contact];
        $this->ojsMailer->sendEventMail($name, $staff, $params, $journal);
    }
}
