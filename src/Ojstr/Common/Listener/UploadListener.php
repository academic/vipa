<?php

namespace Ojstr\Common\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class UploadListener {

    protected $container;

    public function __construct($doctrine, Container $container = null) {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    public function onUpload(PostPersistEvent $event) {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $file = $event->getFile();

        $response['files'] = array(
            'name' => $file->getFileName(),
            'size' => $file->getSize(),
            'url' => '',
            'delete_url' => '',
            'delete_type' => 'DELETE'
        );
        return $response;
    }

}
