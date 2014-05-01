<?php

namespace Ojstr\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;

/**
 * Theme
 */
class Theme extends \Ojstr\Common\Entity\GenericExtendedEntity implements Translatable {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $baseTheme;

    /**
     * @var datetime $created 
     */
    private $created;

    /**
     * @var datetime $updated
     */
    private $updated;

    /**
     * @var datetime $contentChanged
     */
    private $contentChanged;

    /**
     * @var datetime
     */
    private $deletedAt;

    public function getUpdated() {
        return $this->updated;
    }

    public function getContentChanged() {
        return $this->contentChanged;
    }

    /**
     * Translateable locale field
     */
    private $locale;

    public function setTranslatableLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Theme
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Theme
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set baseTheme
     *
     * @param boolean $baseTheme
     * @return Theme
     */
    public function setBaseTheme($baseTheme) {
        $this->baseTheme = $baseTheme;
        return $this;
    }

    /**
     * Get baseTheme
     *
     * @return boolean 
     */
    public function getBaseTheme() {
        return $this->baseTheme;
    }

}
