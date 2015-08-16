<?php

namespace Ojs\Common\Entity;

use Prezent\Doctrine\Translatable\Annotation as Prezent;

trait TranslateableTrait
{
    /**
     * @var
     */
    protected $currentTranslation;

    /**
     * @Prezent\CurrentLocale
     * @GRID\Column(title="currentLocale")
     */
    protected $currentLocale;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * Translateable locale field
     */
    protected $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public function getTranslatableLocale()
    {
        return $this->locale;
    }

    /**
     * Alias for getTranslatableLocale
     * @return mixed
     */
    public function getLocale()
    {
        return $this->getTranslatableLocale();
    }

    /**
     * Alias for setTranslatableLocale
     * @param $locale
     * @return TranslateableTrait
     */
    public function setLocale($locale)
    {
        return $this->setTranslatableLocale($locale);
    }

    /**
     * @return mixed
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @param mixed $currentLocale
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;
    }

    /**
     * @return mixed
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param mixed $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }
}
