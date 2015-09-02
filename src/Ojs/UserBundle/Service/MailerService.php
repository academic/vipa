<?php

namespace Ojs\UserBundle\Service;

use Ojs\JournalBundle\Entity\Journal;
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
    /** @var  string */
    protected $systemEmail;

    /**
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templating
     * @param $systemEmail
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, $systemEmail)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->systemEmail = $systemEmail;
    }

    /**
     * Send a mail or add to spool, then log to db.
     * @param  Mail $mail
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
        $message = \Swift_Message::newInstance()
            ->setSubject($mail->subject)
            ->setFrom($mail->from)
            ->setTo($mail->to)
            ->setBody($mail->body)
            ->setContentType('text/html');

        return $this->mailer->send($message);
    }
}
