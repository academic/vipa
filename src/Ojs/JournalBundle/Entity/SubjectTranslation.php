<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class SubjectTranslation extends AbstractPersonalTranslation
{

    /**
     * Convinient constructor
     *
     */
    public function __construct()
    {
    }
    protected $object;
}