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
        $em     = $args->getEntityManager();

        if ($object instanceof MultipleMail) {

            $activation_code = md5(uniqid(null, true));
            $object->setActivationCode($activation_code);
            $object->setIsConfirmed(false);
            $user = $em->getRepository('OjsUserBundle:User')->findOneBy(array('id'=> $object->getUserId()));
            $body = $this->container->get('templating')->render('OjsUserBundle:Mails/User:multipleMailConfirm.html.twig',['multipleMail' => $object]);
            $this->container->get('ojs_mailer')->send('Multiple Mail Activation',$body,$object->getMail(),$user->getFullName());
        }
    }
}
