<?php

namespace Ojs\Common\Listener;

use Ojs\Common\Helper\FileHelper;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Ojs\Common\Helper\ImageResizeHelper;

class UploadListener
{

    protected $rootDir;

    public function __construct($rootDir = './')
    {
        $this->rootDir = $rootDir;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $file = $event->getFile();
        $uploadType = $event->getType();
        $filePath = $file->getPathName();
        $fileName = $file->getFileName();
        $fileSize = $file->getSize();

        // move to folder or create a nested folder structure
        $fileHelper = new FileHelper();
        $uploadRootPath = $this->rootDir . '/../web/uploads/' . $uploadType . '/';
        /**
         * @var string $uploaNestedDirs generated nested folder structure under rootpath. c33b/f671/1712/
         */
        $nestedDirs = $fileHelper->generatePath($fileName, true, $uploadRootPath);
        rename($filePath, $uploadRootPath . $nestedDirs . $fileName);
        $fileDir = $uploadRootPath . $nestedDirs;
        $uploadUrl = str_replace($uploadRootPath, $uploadType, $fileDir);
        if ($uploadType === 'avatarfiles') {
            $helper = new ImageResizeHelper(array(
                    'image_name' => $fileName,
                    'upload_dir' => $fileDir,
                    'upload_url' => $uploadUrl
                )
            );
            $helper->resize();
        }
        $response['files'] = array(
            'name' => $file->getFileName(),
            'size' => $fileSize,
            'url' => ''
        );

        return $response;
    }

}
