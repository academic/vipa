<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dump\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\MailLog;
use Ojstr\UserBundle\Entity\Model\Mail;

/**
 * Mail controller.
 *
 */
class MailController extends Controller
{
    protected $container;
    protected $mailer;

    function __construct($container, $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
    }

    /**
     * Send a mail or add to spool, then log to db.
     * @param Mail $mail
     * @return mixed
     */
    public function send(Mail $mail)
    {
        if (isset($mail->template)) {
            $mail->templateData = isset($mail->templateData) ? $mail->templateData : array();
            $mail->body = $this->container->get('templating')->render($mail->template, $mail->templateData);
        }
        if (!isset($mail->from)) {
            $mail->from = $this->container->getParameter('system_email');
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
