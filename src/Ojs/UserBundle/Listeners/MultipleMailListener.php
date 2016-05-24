<?php

namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\UserBundle\Entity\MultipleMail;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MultipleMailListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();

        if ($object instanceof MultipleMail) {

            $activationCode = md5(uniqid(null, true));
            $object->setActivationCode($activationCode);
            $object->setIsConfirmed(false);
            $body = $this->container->get('templating')->render('OjsUserBundle:Mails/User:multipleMailConfirm.html.twig',['multipleMail' => $object]);
            $this->container->get('ojs_mailer')->send('Multiple Mail Activation',$body,$object->getMail(),$object->getUser()->getFirstName());
        }
    }
}
