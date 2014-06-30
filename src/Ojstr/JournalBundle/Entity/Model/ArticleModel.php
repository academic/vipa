<?php

namespace Ojstr\JournalBundle\Entity\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Article Meta 
 * Article entity extends from this entity
 * @ExclusionPolicy("all")
 */
class ArticleModel extends \Ojstr\Common\Entity\GenericExtendedEntity {

    const ArticleStatusSubmitted = 0;
    const ArticleStatusReviewing = 1;
    const ArticleStatusEditing = 2;
    const ArticleStatusPublished = 3;

    /**
     * (optional)
     * @var string
     * @Expose
     */
    protected $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     * @Expose
     */
    protected $otherId;

    /**
     * @var integer
     * @Expose
     */
    protected $journalId;

    /**
     * Original article title
     * @var string
     * @Expose
     */
    protected $title;

    /**
     * Roman transliterated title
     * @var string
     * @Expose
     */
    protected $titleTransliterated;

    /**
     * @var string
     * @Expose
     */
    protected $subtitle;

    /**
     * @var string
     * @Expose
     */
    protected $keywords;

    /**
     * Some artilce carries no authorship
     * @var boolean
     * @Expose
     */
    protected $isAnonymous;

    /**
     * @var \DateTime
     * @Expose
     */
    protected $pubdate;

    /**
     * @var string
     * @Expose
     */
    protected $pubdateSeason;

    /**
     * @var string
     * @Expose
     */
    protected $part;

    /**
     * @var integer
     * @Expose
     */
    protected $firstPage;

    /**
     * @var integer
     * @Expose
     */
    protected $lastPage;

    /**
     * @var string
     * @Expose
     */
    protected $uri;

    /**
     * Original abstract
     * @var string
     * @Expose
     */
    protected $abstract;

    /**
     * (optional) English transliterated abstract
     * @var string
     * @Expose
     */
    protected $abstractTransliterated;

    public function getKeywords() {
        return $this->keywords;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $keywords;
    }

    /**
     * Set doi
     *
     * @param string $doi
     * @return Article
     */
    public function setDoi($doi) {
        $this->doi = $doi;
        return $this;
    }

    /**
     * Get doi
     *
     * @return string
     */
    public function getDoi() {
        return $this->doi;
    }

    /**
     * Set otherId
     *
     * @param string $otherId
     * @return Article
     */
    public function setOtherId($otherId) {
        $this->otherId = $otherId;
        return $this;
    }

    /**
     * Get otherId
     *
     * @return string
     */
    public function getOtherId() {
        return $this->otherId;
    }

    /**
     * Set journalId
     *
     * @param integer $journalId
     * @return Article
     */
    public function setJournalId($journalId) {
        $this->journalId = $journalId;
        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer
     */
    public function getJournalId() {
        return $this->journalId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
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
     * Set titleTransliterated
     *
     * @param string $titleTransliterated
     * @return Article
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
     * @return Article
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
     * Set isAnonymous
     *
     * @param boolean $isAnonymous
     * @return Article
     */
    public function setIsAnonymous($isAnonymous) {
        $this->isAnonymous = $isAnonymous;
        return $this;
    }

    /**
     * Get isAnonymous
     *
     * @return boolean
     */
    public function getIsAnonymous() {
        return $this->isAnonymous;
    }

    /**
     * Set pubdate
     *
     * @param \DateTime $pubdate
     * @return Article
     */
    public function setPubdate($pubdate) {
        $this->pubdate = $pubdate;
        return $this;
    }

    /**
     * Get pubdate
     *
     * @return \DateTime
     */
    public function getPubdate() {
        return $this->pubdate;
    }

    /**
     * Set pubdateSeason
     *
     * @param string $pubdateSeason
     * @return Article
     */
    public function setPubdateSeason($pubdateSeason) {
        $this->pubdateSeason = $pubdateSeason;
        return $this;
    }

    /**
     * Get pubdateSeason
     *
     * @return string
     */
    public function getPubdateSeason() {
        return $this->pubdateSeason;
    }

    /**
     * Set part
     *
     * @param string $part
     * @return Article
     */
    public function setPart($part) {
        $this->part = $part;
        return $this;
    }

    /**
     * Get part
     *
     * @return string
     */
    public function getPart() {
        return $this->part;
    }

    /**
     * Set firstPage
     *
     * @param integer $firstPage
     * @return Article
     */
    public function setFirstPage($firstPage) {
        $this->firstPage = $firstPage;
        return $this;
    }

    /**
     * Get firstPage
     *
     * @return integer
     */
    public function getFirstPage() {
        return $this->firstPage;
    }

    /**
     * Set lastPage
     *
     * @param integer $lastPage
     * @return Article
     */
    public function setLastPage($lastPage) {
        $this->lastPage = $lastPage;
        return $this;
    }

    /**
     * Get lastPage
     *
     * @return integer
     */
    public function getLastPage() {
        return $this->lastPage;
    }

    /**
     * Set uri
     *
     * @param string $uri
     * @return Article
     */
    public function setUri($uri) {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return Article
     */
    public function setAbstract($abstract) {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract() {
        return $this->abstract;
    }

    /**
     * Set abstractTransliterated
     *
     * @param string $abstractTransliterated
     * @return Article
     */
    public function setAbstractTransliterated($abstractTransliterated) {
        $this->abstractTransliterated = $abstractTransliterated;
        return $this;
    }

    /**
     * Get abstractTransliterated
     *
     * @return string
     */
    public function getAbstractTransliterated() {
        return $this->abstractTransliterated;
    }

}
