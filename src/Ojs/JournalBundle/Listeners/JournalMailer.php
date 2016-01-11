<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalEvents;

class JournalMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            JournalEvents::POST_UPDATE => 'onJournalPostUpdate'
        );
    }

    /**
     * @param JournalEvent $event
     */
    public function onJournalPostUpdate(JournalEvent $event)
    {
        $mailUsers = $this->em->getRepository('OjsUserBundle:User')->findUsersByJournalRole(
            ['ROLE_JOURNAL_MANAGER', 'ROLE_EDITOR']
        );

        foreach ($mailUsers as $user) {
            $this->ojsMailer->sendToUser(
                $user,
                'Journal Event : Journal Changed :' . $event->getJournal()->getTitle(),
                'Journal Event : Journal Changed by -> '.$this->user->getUsername()
            );
        }
    }
}
