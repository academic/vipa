<?php

namespace Vipa\JournalBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Service\Mailer;
use Vipa\JournalBundle\Entity\JournalAnnouncement;
use Vipa\JournalBundle\Event\JournalAnnouncement\JournalAnnouncementEvents;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager */
    private $em;

    /** @var Mailer */
    private $vipaMailer;

    /**
     * @param EntityManager $em
     * @param Mailer $vipaMailer
     */
    public function __construct(EntityManager $em, Mailer $vipaMailer)
    {
        $this->em = $em;
        $this->vipaMailer = $vipaMailer;
    }


    public static function getSubscribedEvents()
    {
        return array(
            JournalAnnouncementEvents::POST_CREATE => 'sendAnnouncement'
        );
    }

    public function sendAnnouncement(JournalItemEvent $itemEvent)
    {
        /** @var JournalAnnouncement $announcement */
        $announcement = $itemEvent->getItem();

        $mailList = $this->em
            ->getRepository('VipaJournalBundle:SubscribeMailList')
            ->findBy(['journal' => $announcement->getJournal()]);

        foreach ($mailList as $mail) {
            $this->vipaMailer->send(
                $announcement->getTitle(),
                $announcement->getContent(),
                $mail->getMail(),
                $mail->getMail()
            );
        }
    }
}
