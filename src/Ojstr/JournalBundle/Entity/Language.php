<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * Language
 */
class Language {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $languageTranslated;

    /**
     * @var string
     */
    private $code;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Language
     */
    public function setLanguage($language) {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set languageTranslated
     *
     * @param string $languageTranslated
     * @return Language
     */
    public function setLanguageTranslated($languageTranslated) {
        $this->languageTranslated = $languageTranslated;
        return $this;
    }

    /**
     * Get languageTranslated
     *
     * @return string 
     */
    public function getLanguageTranslated() {
        return $this->languageTranslated;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Language
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

}
