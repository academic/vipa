<?php

namespace Ojs\CmsBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

abstract class File extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @var string
     */
    private $description;

    /**
     * @Prezent\Translations(targetEntity="Ojs\CmsBundle\Entity\FileTranslation")
     */
    protected $translations;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $size;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->translations->getName();
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->translations->setName($name);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\CmsBundle\Entity\FileTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }

        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }

        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }

        $defaultTranslation = $this->translations->get($this->getDefaultLocale());

        if (!$translation = $this->translations->get($locale)) {
            $translation = new FileTranslation();

            if (!is_null($defaultTranslation)) {
                $translation->setName($defaultTranslation->setName());
            }

            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }

        $this->currentTranslation = $translation;

        return $translation;
    }
}