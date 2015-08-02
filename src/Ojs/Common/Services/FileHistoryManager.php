<?php

namespace Ojs\Common\Services;

use Jb\Bundle\FileUploaderBundle\Entity\FileHistory;
use Jb\Bundle\FileUploaderBundle\Service\FileHistoryManager as BaseFileHistoryManager;
use Ojs\Common\Helper\FileHelper;

class FileHistoryManager extends BaseFileHistoryManager
{
    /**
     * {@inheritDoc}
     */
    public function create($fileName, $originalName, $type, $userId)
    {
        $fileHistory = new FileHistory();
        $fileHelper = new FileHelper();

        $fileName = $fileHelper->generatePath($fileName, false) . $fileName;

        $fileHistory->setFileName($fileName);
        $fileHistory->setOriginalName($originalName);
        $fileHistory->setType($type);
        if ($userId == null) {
            $fileHistory->setUserId($this->getAuthUserId());
        } else {
            $fileHistory->setUserId($userId);
        }

        return $fileHistory;
    }
}

