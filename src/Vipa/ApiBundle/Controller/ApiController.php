<?php

namespace Vipa\ApiBundle\Controller;

use Doctrine\ORM\NoResultException;
use Vipa\CoreBundle\Events\CoreEvents;
use Vipa\CoreBundle\Events\PermissionEvent;
use Vipa\JournalBundle\Entity\Publisher;
use Vipa\UserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Api Base Controller controller.
 *
 */
class ApiController extends FOSRestController
{

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes The attributes
     * @param mixed $object The object
     * @param $field
     *
     * @throws \LogicException
     * @return bool
     */
    protected function isGranted($attributes, $object = null, $field = null)
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new PermissionEvent($this, $attributes, $object, $field);
        $dispatcher->dispatch(CoreEvents::OJS_PERMISSION_CHECK, $event);
        if (!is_null($event->getResult())) {
            return $event->getResult();
        }


        return $this->container->get('security.authorization_checker')->isGranted($attributes, $object, $field);
    }

    protected function isGrantedForPublisher(Publisher $publisher)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->isAdmin()) {
            return true;
        }
        foreach ($publisher->getPublisherManagers() as $manager) {
            if ($manager->getUser()->getId() == $user->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param  mixed $entity
     * @param  string $message custom not found message
     * @return boolean
     * @throws NoResultException
     */
    protected function throw404IfNotFound($entity, $message = 'Not Found')
    {
        if (!$entity) {
            throw new NotFoundHttpException('The resource not found.');
        }

        return true;
    }
}
