<?php

namespace Ojstr\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Journal
 * @ExclusionPolicy("all")
 */
class Journal extends \Ojstr\Common\Entity\GenericExtendedEntity implements Translatable {

    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Expose
     */
    private $title;

    /**
     * @var string
     * @Expose
     */
    private $titleAbbr;

    /**
     * @var string
     * @Expose
     */
    private $titleTransliterated;

    /**
     * @var string
     * @Expose
     */
    private $subtitle;

    /**
     * @var string
     * @Expose
     */
    private $issn;

    /**
     * @var string
     */
    private $eissn;

    /**
     * @var \DateTime
     * @Expose
     */
    private $firstPublishDate;

    /**
     * @var string
     * @Expose
     */
    private $period;

    /**
     * @var string
     * @Expose
     */
    private $url;

    /**
     * @var integer
     * @Expose
     */
    private $country;

    /**
     * @var integer
     * @Expose
     */
    private $publishStatus;

    /**
     * @var integer
     * @Expose
     */
    private $status;

    /**
     * @var string
     * @Expose
     */
    private $image;

    /**
     * @var string
     * @Expose
     */
    private $scope;

    /**
     * @var string
     * @Expose
     */
    private $mission;

    /**
     * @var integer
     * @Expose
     */
    private $themeId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $issues;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $languages;

    /**
     * Constructor
     */
    public function __construct() {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->issues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param \Ojstr\JournalBundle\Entity\Lang $language
     * @return Journal
     */
    public function addLanguage(\Ojstr\JournalBundle\Entity\Lang $language) {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * @param \Ojstr\JournalBundle\Entity\Lang $language
     */
    public function removeLanguage(\Ojstr\JournalBundle\Entity\Lang $language) {
        $this->languages->removeElement($language);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages() {
        return $this->languages;
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
     * Set titleTransliterated
     *
     * @param string $titleTransliterated
     * @return Journal
     */
    public function setTitleTransliterated($titleTransliterated) {
        $this->titleTransliterated = $titleTransliterated;
        return $this;
    }

    /**
     * Get titleTransliterated
     *
     * @return string 
     */
    public function getTitleTransliterated() {
        return $this->titleTransliterated;
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
     * Set image
     *
     * @param string $image
     * @return Journal
     */
    public function setCoverImage($image) {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getCoverImage() {
        return $this->image;
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

    /**
     * Add articles
     *
     * @param \Ojstr\JournalBundle\Entity\Article $articles
     * @return Journal
     */
    public function addArticle(\Ojstr\JournalBundle\Entity\Article $article) {
        $this->articles[] = $article;
        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Ojstr\JournalBundle\Entity\Article $article
     */
    public function removeArticle(\Ojstr\JournalBundle\Entity\Article $article) {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles() {
        return $this->articles;
    }

    /**
     * Add issue
     *
     * @param \Ojstr\JournalBundle\Entity\Issue $issues
     * @return Journal
     */
    public function addIssue(\Ojstr\JournalBundle\Entity\Issue $issue) {
        $this->issues[] = $issue;
        return $this;
    }

    /**
     * Remove issue
     *
     * @param \Ojstr\JournalBundle\Entity\Issue $issue
     */
    public function removeIssue(\Ojstr\JournalBundle\Entity\Issue $issue) {
        $this->issues->removeElement($issue);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIssues() {
        return $this->issues;
    }

    /**
     * Remove articles
     *
     * @param \Ojstr\UserBundle\Entity\User $users
     */
    public function removeUser(\Ojstr\UserBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Add articles
     *
     * @param \Ojstr\UserBundle\Entity\User $users
     * @return Journal
     */
    public function addUser(\Ojstr\UserBundle\Entity\User $users) {
        $this->users[] = $users;

        return $this;
    }

}
