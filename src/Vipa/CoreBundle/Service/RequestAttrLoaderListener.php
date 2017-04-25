<?php

namespace Vipa\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Vipa\AdminBundle\Entity\SystemSetting;
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
        $systemSetting = $this->em->getRepository('VipaAdminBundle:SystemSetting')->findOneBy([]);
        if(!$systemSetting){
            $systemSetting = new SystemSetting();
            $systemSetting
                ->setArticleSubmissionActive(true)
                ->setJournalApplicationActive(true)
                ->setPublisherApplicationActive(true)
                ->setUserRegistrationActive(true)
                ->setSystemFooterScript('')
            ;
            $this->em->persist($systemSetting);
            $this->em->flush();
        }
        $attributes->set('_system_setting', $systemSetting);

        return;
    }
}
