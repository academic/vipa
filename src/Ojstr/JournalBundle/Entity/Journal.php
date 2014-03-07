<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Journal
 */
class Journal {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $titleAbbr;

    /**
     * @var string
     */
    private $titleTranslated;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var string
     */
    private $issn;

    /**
     * @var string
     */
    private $eissn;

    /**
     * @var \DateTime
     */
    private $firstPublishDate;

    /**
     * @var string
     */
    private $period;

    /**
     * @var string
     */
    private $url;

    /**
     * @var integer
     */
    private $country;

    /**
     * @var integer
     */
    private $publishStatus;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $coverImage;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $mission;

    /**
     * @var integer
     */
    private $themeId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Journal
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set titleAbbr
     *
     * @param string $titleAbbr
     * @return Journal
     */
    public function setTitleAbbr($titleAbbr) {
        $this->titleAbbr = $titleAbbr;
        return $this;
    }

    /**
     * Get titleAbbr
     *
     * @return string 
     */
    public function getTitleAbbr() {
        return $this->titleAbbr;
    }

    /**
     * Set titleTranslated
     *
     * @param string $titleTranslated
     * @return Journal
     */
    public function setTitleTranslated($titleTranslated) {
        $this->titleTranslated = $titleTranslated;
        return $this;
    }

    /**
     * Get titleTranslated
     *
     * @return string 
     */
    public function getTitleTranslated() {
        return $this->titleTranslated;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Journal
     */
    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * Set issn
     *
     * @param string $issn
     * @return Journal
     */
    public function setIssn($issn) {
        $this->issn = $issn;
        return $this;
    }

    /**
     * Get issn
     *
     * @return string 
     */
    public function getIssn() {
        return $this->issn;
    }

    /**
     * Set eissn
     *
     * @param string $eissn
     * @return Journal
     */
    public function setEissn($eissn) {
        $this->eissn = $eissn;
        return $this;
    }

    /**
     * Get eissn
     *
     * @return string 
     */
    public function getEissn() {
        return $this->eissn;
    }

    /**
     * Set firstPublishDate
     *
     * @param \DateTime $firstPublishDate
     * @return Journal
     */
    public function setFirstPublishDate($firstPublishDate) {
        $this->firstPublishDate = $firstPublishDate;
        return $this;
    }

    /**
     * Get firstPublishDate
     *
     * @return \DateTime 
     */
    public function getFirstPublishDate() {
        return $this->firstPublishDate;
    }

    /**
     * Set period
     *
     * @param string $period
     * @return Journal
     */
    public function setPeriod($period) {
        $this->period = $period;
        return $this;
    }

    /**
     * Get period
     *
     * @return string 
     */
    public function getPeriod() {
        return $this->period;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Journal
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set country
     *
     * @param integer $country
     * @return Journal
     */
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return integer 
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set publishStatus
     *
     * @param integer $publishStatus
     * @return Journal
     */
    public function setPublishStatus($publishStatus) {
        $this->publishStatus = $publishStatus;
        return $this;
    }

    /**
     * Get publishStatus
     *
     * @return integer 
     */
    public function getPublishStatus() {
        return $this->publishStatus;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Journal
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set coverImage
     *
     * @param string $coverImage
     * @return Journal
     */
    public function setCoverImage($coverImage) {
        $this->coverImage = $coverImage;
        return $this;
    }

    /**
     * Get coverImage
     *
     * @return string 
     */
    public function getCoverImage() {
        return $this->coverImage;
    }

    /**
     * Set scope
     *
     * @param string $scope
     * @return Journal
     */
    public function setScope($scope) {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Get scope
     *
     * @return string 
     */
    public function getScope() {
        return $this->scope;
    }

    /**
     * Set mission
     *
     * @param string $mission
     * @return Journal
     */
    public function setMission($mission) {
        $this->mission = $mission;
        return $this;
    }

    /**
     * Get mission
     *
     * @return string 
     */
    public function getMission() {
        return $this->mission;
    }

    /**
     * Set themeId
     *
     * @param integer $themeId
     * @return Journal
     */
    public function setThemeId($themeId) {
        $this->themeId = $themeId;
        return $this;
    }

    /**
     * Get themeId
     *
     * @return integer 
     */
    public function getThemeId() {
        return $this->themeId;
    }

}
