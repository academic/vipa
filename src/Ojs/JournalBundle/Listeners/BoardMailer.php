<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\Board;
use Ojs\JournalBundle\Event\Board\BoardEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

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
            BoardEvents::PRE_DELETE  => 'onBoardPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onBoardPostCreate(JournalItemEvent $event)
    {
        $this->sendBoardMail($event, BoardEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onBoardPostUpdate(JournalItemEvent $event)
    {
        $this->sendBoardMail($event, BoardEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onBoardPreDelete(JournalItemEvent $event)
    {
        $this->sendBoardMail($event, BoardEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     */
    private function sendBoardMail(JournalItemEvent $event, string $name)
    {
        /** @var Board $board */
        $board = $event->getItem();
        $journal = $board->getJournal();
        $staff = $this->mailer->getJournalStaff();

        $params = [
            'journal' => (string) $journal,
            'board'   => (string) $board,
        ];

        $this->mailer->sendEventMail($name, $staff, $params, $journal);
    }
}
