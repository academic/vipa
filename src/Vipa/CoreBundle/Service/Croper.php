<?php

namespace Vipa\CoreBundle\Service;

use Jb\Bundle\FileUploaderBundle\Service\Croper as BaseCroper;

class Croper extends BaseCroper
{

    /**
     * Crop an image
     *
     * @param string $endpoint
     * @param array $data
     *
     * @return string
     */
    public function crop($endpoint, array $data)
    {
        /** @var \Vipa\CoreBundle\Service\CropFileManager $cropManager */
        $cropManager = $this->cropManager;
        // Throw ValidationException if there is an error
        $this->validator->validate($endpoint, $data, 'crop_validators');

        // Generate croped image
        $cropedFile = $cropManager->transformFileWithEndpoint($endpoint, $data);

        // Save it to filesystem using gaufrette
        $this->cropManager->saveTransformedFile($endpoint, $cropedFile, $data);

        // Return data
        return array(
            'filepath' => $this->resolvers->getResolver($this->getCropResolver($endpoint))->getUrl($data['filename']),
            'filename' => $data['filename']
        );
    }
}
