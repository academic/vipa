<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class ArticleTypesTranslation extends AbstractPersonalTranslation
{
    protected $object;
}
