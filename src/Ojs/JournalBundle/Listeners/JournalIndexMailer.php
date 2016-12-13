<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalIndex;
use Ojs\JournalBundle\Event\JournalIndex\JournalIndexEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class JournalIndexMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalIndexEvents::POST_CREATE => 'onJournalIndexPostCreate',
            JournalIndexEvents::POST_UPDATE => 'onJournalIndexPostUpdate',
            JournalIndexEvents::PRE_DELETE  => 'onJournalIndexPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalIndexPostCreate(JournalItemEvent $event)
    {
        $this->sendIndexMail($event, JournalIndexEvents::POST_CREATE, true);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalIndexPostUpdate(JournalItemEvent $event)
    {
        $this->sendIndexMail($event, JournalIndexEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalIndexPreDelete(JournalItemEvent $event)
    {
        $this->sendIndexMail($event, JournalIndexEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     * @param bool $toAdmin
     */
    private function sendIndexMail(JournalItemEvent $event, string $name, $toAdmin = false)
    {
        /** @var JournalIndex $index */
        $index = $event->getItem();
        $journal = $index->getJournal();
        $staff = $this->mailer->getJournalStaff();

        $params = [
            'journal' => (string) $journal,
            'index'   => (string) $index,
        ];

        $this->mailer->sendEventMail($name.'to.users', $staff, $params, $journal);

        if (!$toAdmin) {
            return;
        }

        $link = $this->router->generate('ojs_admin_journal_edit', ['id' => $journal->getId()]);
        $params = array_merge($params, ['journal.edit' => $link]);

        $this->mailer->sendEventMail($name.'to.admins', $this->mailer->getAdmins(), $params, $journal);
    }
}
