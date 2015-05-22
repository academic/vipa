<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 6.02.15
 * Time: 16:04
 */
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\EntityManager;
use Ojs\UserBundle\Entity\EventLog;
use Ojs\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ojs\Common\Params\UserEventLogParams;
use Symfony\Component\HttpFoundation\RequestStack;

class UserSubscriber implements EventSubscriberInterface
{
    /** @var \Swift_Mailer  */
    protected $mailer;
    /** @var \Twig_Environment  */
    protected $twig;
    /** @var EntityManager  */
    protected $em;
    /** @var RequestStack  */
    protected $request;
    /** @var  string */
    protected $systemEmail;

    /**
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     * @param EntityManager     $em
     * @param RequestStack           $request
     * @param $systemEmail
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, EntityManager $em, RequestStack $request, $systemEmail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $em;
        $this->request = $request;
        $this->systemEmail = $systemEmail;
    }

    public static function getSubscribedEvents()
    {
        return [
            'user.register.complete' => 'onRegisterComplete',
            'user.password.change' => 'onPasswordChange',
            'user.password.reset' => 'onPasswordReset',
        ];
    }

    public function onRegisterComplete(UserEvent $event)
    {
        $message = $this->mailer->createMessage()
            ->setSubject('Registration Complete')
            ->setFrom($this->systemEmail)
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->twig->render('OjsUserBundle:Mails:User/confirmEmail.html.twig', ['user' => $event->getUser()])
            )
            ->setContentType('text/html');
        $this->mailer->send($message);
    }
    public function onPasswordChange(UserEvent $event)
    {
        $message = $this->mailer->createMessage()
            ->setSubject('Password Change')
            ->setFrom($this->systemEmail)
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->twig->render('OjsUserBundle:Mails:User/password_changed.html.twig', ['user' => $event->getUser()])
            )
            ->setContentType('text/html');
        $this->mailer->send($message);

        try {
            //log as eventlog
            $eventLog = new EventLog();
            $eventLog->setEventInfo(UserEventLogParams::$PASSWORD_CHANGE);
            $eventLog->setIp($this->request->getCurrentRequest()->getClientIp());
            $eventLog->setUserId($event->getUser()->getId());
            $this->em->persist($eventLog);

            $this->em->flush();
        } catch (\Exception $e) {
        }
    }
    public function onPasswordReset(UserEvent $event)
    {
        $message = $this->mailer->createMessage()
            ->setSubject('Password Reset Completed')
            ->setFrom($this->systemEmail)
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->twig->render('OjsUserBundle:Mails:User/password_reset_successful.html.twig', ['user' => $event->getUser()])
            )
            ->setContentType('text/html');
        $this->mailer->send($message);
    }
}
