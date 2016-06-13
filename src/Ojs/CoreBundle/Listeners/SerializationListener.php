<?php

namespace Ojs\CoreBundle\Listeners;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;

class SerializationListener implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    static public function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'method' => 'onPostSerialize'),
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        if(method_exists($object, 'display')){
            foreach($event->getObject()->display() as $itemKey => $item){
                if(is_object($item) && method_exists($item, 'count') && !is_string($item)){
                    try{
                        $visitor = $event->getVisitor();
                        if($visitor instanceof JsonSerializationVisitor){
                            $event->getVisitor()->addData($itemKey.'_count', $item->count());
                        }
                    }catch (\Exception $e){
                        return;
                    }
                }
            }
        }
    }
}
