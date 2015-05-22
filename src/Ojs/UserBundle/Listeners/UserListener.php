<?php
namespace Ojs\UserBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Ojs\UserBundle\Entity\EventLog;
use Ojs\UserBundle\Entity\User;
use Ojs\Common\Params\UserEventLogParams;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class UserListener
{
    /** @var RequestStack  */
    protected $request;

    /**
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * Every new user log to event log
     * @param  LifecycleEventArgs|RequestStack $args
     * @link http://docs.doctrine-project.org/en/latest/reference/events.html#postupdate-postremove-postpersist
     * @return Response                   never null
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if (php_sapi_name() != 'cli') {
            $entity = $args->getEntity();
            $entityManager = $args->getEntityManager();

            // perhaps you only want to act on some "User" entity
            if ($entity instanceof User) {

                //log as eventlog
                $event = new EventLog();
                $event->setEventInfo(UserEventLogParams::$USER_ADD);
                $event->setIp($this->request->getCurrentRequest()->getClientIp());
                $event->setUserId($entity->getId());
                $entityManager->persist($event);

                $entityManager->flush();
            }
        }
    }
}
