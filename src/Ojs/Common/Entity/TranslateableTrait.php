<?php

namespace Ojs\Common\Entity;

trait TranslateableTrait
{
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
}
