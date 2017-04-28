<?php

namespace Vipa\CoreBundle\Service\Search;

use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class $this
 * @package Vipa\CoreBundle\Service
 */
class ElasticaTransformListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'doPostTransform',
        );
    }

    public function doPostTransform(TransformEvent $event)
    {
        /** @var Document $document */
        $document = $event->getDocument();
        if($document->has('tags')){
            $explodeTags = array_map('trim', array_values(array_filter(explode(',', $document->get('tags')))));
            $document->set('tags', $explodeTags);
        }
        if($document->has('keywords')){
            $explodeKeywords = array_map('trim', array_values(array_filter(explode(',', $document->get('keywords')))));
            $document->set('keywords', $explodeKeywords);
        }

        return $event;
    }
}
