<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalUser;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalUser\JournalUserEvents;

class JournalUserMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalUserEvents::POST_CREATE => 'onJournalUserPostCreate',
            JournalUserEvents::POST_UPDATE => 'onJournalUserPostUpdate',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalUserPostCreate(JournalItemEvent $itemEvent)
    {
        /** @var JournalUser $journalUser */
        $journalUser = $itemEvent->getItem();

        $this->ojsMailer->sendToUser(
            $journalUser->getUser(),
            'Journal Event : Journal User New',
            'Journal Event : Journal User New -> '.$journalUser->getUser()->getUsername()
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onJournalUserPostUpdate(JournalItemEvent $itemEvent)
    {
        /** @var JournalUser $journalUser */
        $journalUser = $itemEvent->getItem();

        $this->ojsMailer->sendToUser(
            $journalUser->getUser(),
            'Journal Event : Journal User Role Update',
            'Journal Event : Journal User Role Update -> '.$journalUser->getUser()->getUsername()
        );
    }
}
