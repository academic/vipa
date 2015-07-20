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
    }

    public function getTranslatableLocale()
    {
        return $this->locale;
    }
}
