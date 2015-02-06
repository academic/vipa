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

class UserSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            'user.register.complete' => 'onRegisterComplete'
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
} 