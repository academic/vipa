<?php

namespace Ojstr\JournalBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

class Language extends \Ojstr\Entity\GenericEntity {

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
     * @var datetime $created 
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var datetime $updated
     * @Gedmo\Timestampable
     */
    private $updated;

    /**
     * @var datetime $contentChanged
     * @Gedmo\Timestampable()
     */
    private $contentChanged;

    /**
     * @var datetime
     */
    private $deletedAt;

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
