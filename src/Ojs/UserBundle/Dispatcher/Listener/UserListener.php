<?php
/** 
 * Date: 26.01.15
 * Time: 22:05
 */

namespace Ojs\UserBundle\Dispatcher\Listener;


use Ojs\UserBundle\Dispatcher\Event\UserRegisterEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ojs\UserBundle\Dispatcher\UserEvents;
class UserListener extends EventSubscriberInterface{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function getSubscribedEvents()
    {
        return [
            UserEvents::USER_CHANGE_PASSWORD_COMPLETE=>'onChangePasswordComplete',
            UserEvents::USER_PROFILE_UPDATE=>'onProfileUpdate',
            UserEvents::USER_REGISTER_COMPLETE=>'onRegisterComplete'
        ];
    }

    public function onChangePasswordComplete()
    {

    }

    public function onProfileUpdate()
    {

    }

    public function onRegisterComplete(UserRegisterEvent $event)
    {
        $user = $event->getUser();
        $mailer = $this->container->get('mailer');
        /** @var \Swift_Message $message */
        $message = $mailer->createMessage();
        $message->setFrom('emre@emre.xyz')
            ->setTo("k@emre.xyz")
            ->setSubject('Subject')
            ->setBody('Body');
        $mailer->send($message);
    }
}