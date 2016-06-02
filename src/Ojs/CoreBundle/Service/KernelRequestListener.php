<?php

namespace Ojs\CoreBundle\Service;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelRequestListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest')),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        $attributes = $event->getRequest()->attributes;

        //if have any route param return null
        if(!$attributes->has('_route_params')){
            return;
        }
        //for all route params
        foreach($attributes->get('_route_params') as $paramKey => $param){
            // if there is a param which matches with id key
            if(preg_match('/id/', $paramKey)){
                /**
                 * throw not found exception because pg type db max value is 2147483647
                 * @look {https://www.postgresql.org/docs/9.1/static/datatype-numeric.html}
                 */
                if((int)$param && (int)$param > 2147483647){
                    //throw new NotFoundHttpException;
                }
            }
        }
    }
}
