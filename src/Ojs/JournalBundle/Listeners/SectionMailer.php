<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Event\Section\SectionEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\UserBundle\Entity\User;

class SectionMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            SectionEvents::POST_CREATE => 'onSectionPostCreate',
            SectionEvents::POST_UPDATE => 'onSectionPostUpdate',
            SectionEvents::PRE_DELETE => 'onSectionPreDelete',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onSectionPostCreate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(SectionEvents::POST_CREATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'section'           => (string)$itemEvent->getItem(),
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

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onSectionPostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(SectionEvents::POST_UPDATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'section'           => (string)$itemEvent->getItem(),
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

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onSectionPreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getTemplateByEvent(SectionEvents::PRE_DELETE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalStaff() as $user) {
            $transformParams = [
                'section'           => (string)$itemEvent->getItem(),
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
