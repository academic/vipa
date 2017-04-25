<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vipa\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

/**
 * PostTranslation
 */
class JournalPostTranslation  extends AbstractTranslation
{
    use DisplayTrait;
    /**
     * @Prezent\Translatable(targetEntity="Vipa\JournalBundle\Entity\JournalPost")
     */
    protected $translatable;

    private $title;

    private $content;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
