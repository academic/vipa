<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class IssueFileTranslation extends AbstractPersonalTranslation
{
    protected $object;
}
