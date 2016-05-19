<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

class PublisherTranslation extends AbstractTranslation
{
    use DisplayTrait;
    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\Publisher")
     */
    protected $translatable;

    /**
     * @var string
     */
    private $about;

    /**
     * @var string
     */
    private $name;

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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
