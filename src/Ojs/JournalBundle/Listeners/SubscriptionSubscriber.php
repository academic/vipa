<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Service\OjsMailer;
use Ojs\JournalBundle\Event\NewAnnouncementEvent;
use Ojs\JournalBundle\Event\SubscriptionEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager */
    private $em;

    /** @var OjsMailer */
    private $ojsMailer;

    /**
     * @param EntityManager $em
     * @param OjsMailer $ojsMailer
     */
    public function __construct(EntityManager $em, OjsMailer $ojsMailer)
    {
        $this->em = $em;
        $this->ojsMailer = $ojsMailer;
    }


    public static function getSubscribedEvents()
    {
        return array(
            SubscriptionEvents::NEW_ANNOUNCEMENT => 'sendAnnouncement'
        );
    }

    public function sendAnnouncement(NewAnnouncementEvent $event)
    {
        $announcement = $event->getAnnouncement();

        $mailList = $this->em
            ->getRepository('OjsJournalBundle:SubscribeMailList')
            ->findBy(['journal' => $event->getAnnouncement()->getJournal()]);

        foreach ($mailList as $mail) {
            $this->ojsMailer->send(
                $announcement->getTitle(),
                $announcement->getContent(),
                $mail->getMail(),
                $mail->getMail()
            );
        }
    }
}
