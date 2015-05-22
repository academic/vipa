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
}
