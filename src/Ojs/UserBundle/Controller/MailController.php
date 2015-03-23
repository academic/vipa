<?php

namespace Ojs\UserBundle\Controller;

use Symfony\Component\DependencyInjection\Dump\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\UserBundle\Entity\MailLog;
use Ojs\UserBundle\Entity\Model\Mail;

/**
 * Mail controller.
 *
 */
class MailController extends Controller {

    protected $container;
    protected $mailer;

    public function __construct($container, $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
    }

    /**
     * Send a mail or add to spool, then log to db.
     * @param  Mail  $mail
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     * @return mixed
     */
    public function send(Mail $mail, \Ojs\JournalBundle\Entity\Journal $journal)
    {
        if (isset($mail->template)) {
            $mail->templateData = isset($mail->templateData) ? $mail->templateData : array();
            $mail->body = $this->container->get('templating')->render($mail->template, $mail->templateData);
        }
        if (!isset($mail->from)) {
            $mail->from = $this->container->getParameter('system_email');
        }
        if ($journal) {
            $mail->body.="<br><br><hr>" . $journal->getSetting('emailSignature');
        }
        $em = $this->getDoctrine()->getManager();
        $mailLog = new MailLog();
        $message = \Swift_Message::newInstance()
                ->setSubject($mail->subject)
                ->setFrom($mail->from)
                ->setTo($mail->to)
                ->setBody($mail->body)
                ->setContentType('text/html');

        $mailLog->setMailObject($message->toString());
        $mailLog->setRecipientEmail($mail->to);
        $em->persist($mailLog);
        $em->flush();

        return $this->mailer->send($message);
    }

}
