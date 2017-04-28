<?php

namespace Vipa\JournalBundle\Listeners;

use Vipa\JournalBundle\Entity\JournalPost;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\JournalPost\JournalPostEvents;

class JournalPostMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalPostEvents::POST_CREATE => 'onJournalPostPostCreate',
            JournalPostEvents::POST_UPDATE => 'onJournalPostPostUpdate',
            JournalPostEvents::PRE_DELETE  => 'onJournalPostPreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalPostPostCreate(JournalItemEvent $event)
    {
        $this->sendPostMail($event, JournalPostEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalPostPostUpdate(JournalItemEvent $event)
    {
        $this->sendPostMail($event, JournalPostEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalPostPreDelete(JournalItemEvent $event)
    {
        $this->sendPostMail($event, JournalPostEvents::PRE_DELETE);
    }

    private function sendPostMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalPost $post */
        $post = $event->getItem();
        $journal = $post->getJournal();
        $staff = $this->mailer->getJournalStaff();
        $params = ['post' => $post->getTitleTranslations()];
        $this->mailer->sendEventMail($name, $staff, $params, $journal);
    }
}
