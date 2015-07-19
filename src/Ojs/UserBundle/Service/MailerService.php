<?php

namespace Ojs\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\UserBundle\Entity\MailLog;
use Ojs\UserBundle\Entity\Model\Mail;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Mail controller.
 *
 */
class MailerService
{
    /** @var \Swift_Mailer */
    protected $mailer;
    /** @var EngineInterface */
    protected $templating;
    /** @var EntityManager */
    protected $em;
    /** @var  string */
    protected $systemEmail;

    /**
     * @param \Swift_Mailer   $mailer
     * @param EngineInterface $templating
     * @param EntityManager   $em
     * @param $systemEmail
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em, $systemEmail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->em = $em;
        $this->systemEmail = $systemEmail;
    }

    /**
     * Send a mail or add to spool, then log to db.
     * @param  Mail    $mail
     * @param  Journal $journal
     * @return integer
     */
    public function send(Mail $mail, Journal $journal)
    {
        if (isset($mail->template)) {
            $mail->templateData = isset($mail->templateData) ? $mail->templateData : array();
            $mail->body = $this->templating->render($mail->template, $mail->templateData);
        }
        if (!isset($mail->from)) {
            $mail->from = $this->systemEmail;
        }
        if ($journal) {
            $mail->body .= $journal->getSetting('emailSignature');
        }
        $mailLog = new MailLog();
        $message = \Swift_Message::newInstance()
            ->setSubject($mail->subject)
            ->setFrom($mail->from)
            ->setTo($mail->to)
            ->setBody($mail->body)
            ->setContentType('text/html');

        $mailLog->setMailObject($message->toString());
        $mailLog->setRecipientEmail($mail->to);
        $this->em->persist($mailLog);
        $this->em->flush();

        return $this->mailer->send($message);
    }
}
