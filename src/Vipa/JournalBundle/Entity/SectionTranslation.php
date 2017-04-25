<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vipa\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;


class SectionTranslation extends AbstractTranslation
{
    use DisplayTrait;
    /**
     * @Prezent\Translatable(targetEntity="Vipa\JournalBundle\Entity\Section")
     */
    protected $translatable;

    /**
     * @var string
     */
    private $title = '-';

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        if ($title !== null) {
            $this->title = $title;
        }
    }
}
