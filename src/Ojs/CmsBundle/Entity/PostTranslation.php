<?php

namespace Ojs\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * PostTranslation
 */
class PostTranslation  extends AbstractPersonalTranslation
{
    protected $object;
}
