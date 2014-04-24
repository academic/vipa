<?php

namespace Ojstr\JournalBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Theme
 */
class Theme extends TimestampableEntity {

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
