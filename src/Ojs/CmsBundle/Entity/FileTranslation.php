<?php

namespace Ojs\CmsBundle\Entity;

use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

class FileTranslation extends AbstractTranslation
{
    use DisplayTrait;

    /**
     * @Prezent\Translatable(targetEntity="Ojs\CmsBundle\Entity\File")
     */
    protected $translatable;

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
