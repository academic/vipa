<?php

namespace Ojs\CoreBundle\Service;

use FOS\UserBundle\Model\UserInterface;

class OjsMailer
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $mailSender;

    /** @var string */
    private $mailSenderName;

    /**
     * OjsMailer constructor.
     * @param \Swift_Mailer $mailer
     * @param string $mailSender
     * @param string $mailSenderName
     */
    public function __construct(\Swift_Mailer $mailer, $mailSender, $mailSenderName)
    {
        $this->mailer = $mailer;
        $this->mailSender = $mailSender;
        $this->mailSenderName = $mailSenderName;
    }

    /**
     * @param UserInterface $user
     * @param string $subject
     * @param string $body
     */
    public function sendToUser(UserInterface $user, $subject, $body)
    {
        $this->send($subject, $body, $user->getEmail(), $user->getUsername());
    }

    /**
     * @param $subject
     * @param $body
     * @param $toMail
     * @param $toName
     */
    public function send($subject, $body, $toMail, $toName)
    {
        $message = $this->mailer->createMessage();
        $to = array($toMail => $toName);
        $message = $message
            ->setSubject($subject)
            ->addFrom($this->mailSender, $this->mailSenderName)
            ->setTo($to)
            ->setBody($body, 'text/html');
        $this->mailer->send($message);
    }
}
