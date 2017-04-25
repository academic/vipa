<?php

namespace Vipa\CoreBundle\Service;

use Jb\Bundle\FileUploaderBundle\Service\CropFileManager as BaseCropFileManager;

class CropFileManager extends BaseCropFileManager
{
    /**
     * Transform the file
     *
     * @param string $endpoint
     * @param array $data
     *
     * @return \Liip\ImagineBundle\Binary\BinaryInterface
     */
    public function transformFileWithEndpoint($endpoint, array $data)
    {
        try {
            $loaderName = $endpoint.'_original';
            $this->dataManager->getLoader($loaderName);

        }
        catch(\InvalidArgumentException $e) {
            $loaderName = 'original';
        }

        return $this->filterManager->apply(
            $binaryFile = $this->dataManager->find($loaderName, $data['filename']),
            array(
                'filters' => array(
                    'crop'=> array(
                        'start' => array($data['x'], $data['y']),
                        'size' => array($data['width'], $data['height'])
                    )
                )
            )
        );
    }
}
