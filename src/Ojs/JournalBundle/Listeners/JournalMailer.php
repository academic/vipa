<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalEvents;
use Ojs\UserBundle\Entity\User;

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
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(JournalEvents::POST_UPDATE);
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$event->getJournal(),
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
