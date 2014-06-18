<?php

namespace Ojstr\UserBundle\Listeners;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Ojstr\Common\Helper\ImageUploadHelper as ImageUploadHelper;

class AvatarUploadListener {

    protected $container;

    public function __construct($doctrine, Container $container = null) {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }

    public function onUpload(PostPersistEvent $event) {
        $request = $event->getRequest();
        $response = $event->getResponse();
        //$session = $request->getSession();
        //$gallery = $request->get('avatars');
        $file = $event->getFile();
        $helper = new ImageUploadHelper($this->container, array(
            'imageName' => $file->getFileName(),
            'upload_dir' => $this->container->get('kernel')->getRootDir() . '/../web/uploads/avatars/',
            'upload_url' => '/uploads/avatars/'
                )
        );
        $helper->resize();
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
