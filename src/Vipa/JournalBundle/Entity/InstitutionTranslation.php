<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vipa\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

class InstitutionTranslation extends AbstractTranslation
{
    use DisplayTrait;
    /**
     * @Prezent\Translatable(targetEntity="Vipa\JournalBundle\Entity\Institution")
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
