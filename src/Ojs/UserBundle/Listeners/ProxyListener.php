<?php
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\UserBundle\Entity\EventLog;
use Ojs\UserBundle\Entity\Proxy;
use Ojs\Common\Params\ProxyEventLogParams;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ProxyListener
{
    /** @var RequestStack  */
    protected $request;

    /**
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request = null)
    {
        $this->request = $request;
    }

    /**
     * Every new proxy create event log to event log
     * @param  \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @link http://docs.doctrine-project.org/en/latest/reference/events.html#postupdate-postremove-postpersist
     * @return Response                               never null
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if (php_sapi_name() != 'cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            /**
             * perhaps you only want to act on some "Proxy" entity
             * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
             */
            if ($entity instanceof Proxy) {

                //log as eventlog
                $event = new EventLog();
                $event->setUserId($entity->getClientUser()->getId());
                $event->setEventInfo(ProxyEventLogParams::$PROXY_CREATE);
                $event->setAffectedUserId($entity->getProxyUser()->getId());
                $event->setIp($this->request->getCurrentRequest()->getClientIp());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }

    /**
     * Proxy drop event event log function.
     * @param  LifecycleEventArgs $args
     * @return null
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if (php_sapi_name() != 'cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            /**
             * perhaps you only want to act on some "Proxy" entity
             * @link http://docs.doctrine-project.org/en/latest/reference/events.html#listening-and-subscribing-to-lifecycle-events
             */
            if ($entity instanceof Proxy) {

                //log as eventlog
                $event = new EventLog();
                $event->setEventInfo(ProxyEventLogParams::$PROXY_DROP);
                $event->setIp($this->request->getCurrentRequest()->getClientIp());
                $event->setAffectedUserId($entity->getProxyUserId());
                $event->setUserId($entity->getClientUserId());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }
}
