<?php

namespace Ojs\JournalBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Event\NewAnnouncementEvent;
use Ojs\JournalBundle\Event\SubscriptionEvents;
use Swift_Mailer;
use Swift_Mime_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionSubscriber implements EventSubscriberInterface
{
    /** @var EntityManager */
    private $em;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var string */
    private $sender;

    /** @var string */
    private $name;

    /**
     * AnnouncementListener constructor.
     * @param EntityManager $em
     * @param Swift_Mailer $mailer
     * @param string $sender
     * @param string $name
     */
    public function __construct($em, $mailer, $sender, $name)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->name = $name;
    }


    public static function getSubscribedEvents()
    {
        return array(
            SubscriptionEvents::NEW_ANNOUNCEMENT => 'sendAnnouncement'
        );
    }

    public function sendAnnouncement(NewAnnouncementEvent $event)
    {
        /** @var Swift_Mime_Message $message */
        $message = $this->mailer->createMessage();
        $announcement = $event->getAnnouncement();

        $recipients = array();
        $mailList = $this->em
            ->getRepository('OjsJournalBundle:SubscribeMailList')
            ->findBy(['journal' => $event->getAnnouncement()->getJournal()]);

        foreach ($mailList as $mail) {
            $recipients[] = $mail->getMail();
        }

        $message->setTo($recipients);
        $message->setFrom($this->sender, $this->name);
        $message->setSubject($announcement->getTitle());
        $message->setBody($announcement->getContent());

        $this->mailer->send($message);
    }
}
