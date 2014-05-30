<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Citation
 */
class Citation {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $orderNum;

    /**
     * @var Array 
     */
    protected $settings;

    /**
     * 
     */
    public function __construct() {
        $this->settings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Remove setting
     *
     * @param \Ojstr\JournalBundle\Entity\CitationSetting $setting
     */
    public function removeSetting(\Ojstr\JournalBundle\Entity\CitationSetting $setting) {
        $this->settings->removeElement($setting);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSettings() {
        return $this->settings;
    }
    
    public function getSetting($key) {
        return $this->settings[$key];
    }

    /**
     * Add setting
     *
     * @param \Ojstr\JournalBundle\Entity\CitationSetting $setting
     * @return Citation
     */
    public function addSetting(\Ojstr\JournalBundle\Entity\CitationSetting $setting) {
        $this->settings[] = $setting;

        return $this;
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
     * Set source
     *
     * @param string $source
     * @return Citation
     */
    public function setSource($source) {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Citation
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set orderNum
     *
     * @param integer $orderNum
     * @return Citation
     */
    public function setOrderNum($orderNum) {
        $this->orderNum = $orderNum;

        return $this;
    }

    /**
     * Get orderNum
     *
     * @return integer 
     */
    public function getOrderNum() {
        return $this->orderNum;
    }

}
