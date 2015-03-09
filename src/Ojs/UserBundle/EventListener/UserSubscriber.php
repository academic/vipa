<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 6.02.15
 * Time: 16:04
 */

namespace Ojs\UserBundle\EventListener;


use Ojs\UserBundle\Event\UserEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Ojs\Common\Params\UserEventLogParams;

class UserSubscriber implements EventSubscriberInterface
{
    private $container,$em;

    public function __construct(ContainerInterface $container,\Doctrine\ORM\EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            'user.register.complete' => 'onRegisterComplete',
            'user.password.change' => 'onPasswordChange',
            'user.password.reset' => 'onPasswordReset'
        ];
    }

    public function onRegisterComplete(UserEvent $event)
    {
        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject('Registration Complete')
            ->setFrom($this->container->getParameter('system_email'))
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->container->get('twig')->render('OjsUserBundle:Mails:User/confirmEmail.html.twig', ['user' => $event->getUser()])
            )
            ->setContentType('text/html');
        $mailer->send($message);
    }
    public function onPasswordChange(UserEvent $event)
    {
        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject('Password Change')
            ->setFrom($this->container->getParameter('system_email'))
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->container->get('twig')->render('OjsUserBundle:Mails:User/password_changed.html.twig', ['user' => $event->getUser()])
            )
            ->setContentType('text/html');
        $mailer->send($message);

        try{
            //log as eventlog
            $eventLog = new \Ojs\UserBundle\Entity\EventLog();
            $eventLog->setEventInfo(UserEventLogParams::$PASSWORD_CHANGE);
            $eventLog->setIp($this->container->get('request')->getClientIp());
            $eventLog->setUserId($event->getUser()->getId());
            $this->em->persist($eventLog);

            $this->em->flush();
        }catch (Exception $e){}
    }
    public function onPasswordReset(UserEvent $event)
    {
        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject('Password Reset Completed')
            ->setFrom($this->container->getParameter('system_email'))
            ->setTo($event->getUser()->getEmail())
            ->setBody(
                $this->container->get('twig')->render('OjsUserBundle:Mails:User/password_reset_successful.html.twig', ['user' => $event->getUser()])
            )
            ->setContentType('text/html');
        $mailer->send($message);
    }
} 