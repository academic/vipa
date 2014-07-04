Included Packages
-----------------

- Image manipulation using Imagine and Twig Filters https://github.com/avalanche123/AvalancheImagineBundle/blob/develop/README.md


Helpers
-------

- Ojstr/Common/Helper/ImageResizeHelper.php
    - Sample usage : 
    - 
    ``` 
$helper = new ImageResizeHelper(array(
            'imageName' => $file->getFileName(),
            'upload_dir' => $this->container->get('kernel')->getRootDir() . '/../web/uploads/avatars/',
            'upload_url' => '/uploads/avatars/'
                )
        );
        $helper->resize();
``` 
    - You can pass image versions custom configuration. Look image_versions at ImageResizeHelper::_construct
