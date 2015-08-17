<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

class InstitutionTranslation extends AbstractTranslation
{
    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\Institution")
     */
    protected $translatable;

    /**
     * @var string
     */
    private $about;

    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }
}
