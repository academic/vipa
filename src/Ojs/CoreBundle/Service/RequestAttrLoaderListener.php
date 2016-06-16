<?php

namespace Ojs\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestAttrLoaderListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest')),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $attributes = $event->getRequest()->attributes;

        //if has system setting return
        if($attributes->has('_system_setting')){
            return;
        }
        $systemSetting = $this->em->getRepository('OjsAdminBundle:SystemSetting')->findOneBy([]);
        if(!$systemSetting){
            return;
        }
        $attributes->set('_system_setting', $systemSetting);

        return;
    }
}
