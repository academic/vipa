<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CitationSetting
 */
class CitationSetting {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $citationId;

    /**
     * @var string
     */
    private $setting;

    /**
     * @var string
     */
    private $value;

    /**
     *
     * @var \Ojstr\JournalBundle\Entity\Citation 
     */
    protected $citation;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set citationId
     *
     * @param integer $citationId
     * @return CitationSetting
     */
    public function setCitationId($citationId) {
        $this->citationId = $citationId;

        return $this;
    }

    /**
     * Get citationId
     *
     * @return integer 
     */
    public function getCitationId() {
        return $this->citationId;
    }

    /**
     * Set setting
     *
     * @param string $setting
     * @return CitationSetting
     */
    public function setSetting($setting) {
        $this->setting = $setting;

        return $this;
    }

    /**
     * Get setting
     *
     * @return string 
     */
    public function getSetting() {
        return $this->setting;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return CitationSetting
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue() {
        return $this->value;
    }

}
