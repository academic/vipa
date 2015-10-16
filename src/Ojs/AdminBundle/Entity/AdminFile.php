<?php

namespace Ojs\AdminBundle\Entity;

use Ojs\CmsBundle\Entity\File;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Ojs\CoreBundle\Annotation\Display;

/**
 * AdminFile
 */
class AdminFile extends File
{
    /**
     * @Display\File(path="files")
     */
    private $path;
}

