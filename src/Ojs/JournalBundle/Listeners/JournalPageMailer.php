<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\JournalPage;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalPage\JournalPageEvents;

class JournalPageMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            JournalPageEvents::POST_CREATE => 'onJournalPagePostCreate',
            JournalPageEvents::POST_UPDATE => 'onJournalPagePostUpdate',
            JournalPageEvents::PRE_DELETE  => 'onJournalPagePreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalPagePostCreate(JournalItemEvent $event)
    {
        $this->sendPageMail($event, JournalPageEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalPagePostUpdate(JournalItemEvent $event)
    {
        $this->sendPageMail($event, JournalPageEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onJournalPagePreDelete(JournalItemEvent $event)
    {
        $this->sendPageMail($event, JournalPageEvents::PRE_DELETE);
    }

    private function sendPageMail(JournalItemEvent $event, string $name)
    {
        /** @var JournalPage $page */
        $page = $event->getItem();
        $journal = $page->getJournal();
        $staff = $this->ojsMailer->getJournalStaff();
        $params = ['page' => $page->getTitleTranslations()];
        $this->ojsMailer->sendEventMail($name, $staff, $params, $journal);
    }
}
