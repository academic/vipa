<?php

namespace Ojs\JournalBundle\Listeners;

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
            BoardEvents::PRE_DELETE => 'onBoardPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onBoardPostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Board', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onBoardPostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Board', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onBoardPreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Board', 'Deleted');
    }
}
