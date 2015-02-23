<?php
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\UserBundle\Entity\Proxy;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProxyListener
{
    protected $container;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Every new proxy create event log to event log
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @link http://docs.doctrine-project.org/en/latest/reference/events.html#postupdate-postremove-postpersist
     * @return Response never null
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if (php_sapi_name()!='cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            /**
             * perhaps you only want to act on some "Proxy" entity
             * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
             */
            if ($entity instanceof Proxy) {

                //log as eventlog
                $event = new \Ojs\UserBundle\Entity\EventLog();
                $event->setUserId($entity->getClientUser()->getId());
                $event->setEventInfo(\Ojs\Common\Params\UserEventLogParams::$PROXY_CREATE);
                $event->setIp($this->container->get('request')->getClientIp());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }

    /**
     * Proxy drop event event log function.
     * @param LifecycleEventArgs $args
     * @return null
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if (php_sapi_name()!='cli') {

            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            /**
             * perhaps you only want to act on some "Proxy" entity
             * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
             */
            if ($entity instanceof Proxy) {

                //log as eventlog
                $event = new \Ojs\UserBundle\Entity\EventLog();
                $event->setEventInfo(\Ojs\Common\Params\UserEventLogParams::$PROXY_REMOVE);
                $event->setIp($this->container->get('request')->getClientIp());
                $event->setUserId($entity->getClientUserId());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }
}