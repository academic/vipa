<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * Article
 */
class Article extends \Ojstr\Common\Entity\GenericExtendedEntity {

    /**
     * auto-incremented article unique id
     * @var integer
     */
    private $id;

    /**
     * * @var integer
     */
    private $status;

    /**
     * (optional)
     * @var string
     */
    private $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     */
    private $otherId;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * Original article title
     * @var string
     */
    private $title;

    /**
     * English translated title
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
    private $keywords;

    /**
     * Some artilce carries no authorship
     * @var boolean
     */
    private $isAnonymous;

    /**
     * @var \DateTime
     */
    private $pubdate;

    /**
     * @var string
     */
    private $pubdateSeason;

    /**
     * @var string
     */
    private $part;

    /**
     * @var integer
     */
    private $firstPage;

    /**
     * @var integer
     */
    private $lastPage;

    /**
     * @var string
     */
    private $uri;

    /**
     * Original abstract
     * @var string
     */
    private $abstract;

    /**
     * (optional) English translated abstract
     * @var string
     */
    private $abstractTranslated;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getStatus() {
        return $this->status;
    }

    /**
     * 
     * @param bool $translate
     * @return string
     */
    public function getStatusText() {
        return \Ojstr\Common\Params\ArticleParams::statusText($this->status);
    }

    public function setStatus($status) {
        $this->status = $status;
        return $status;
    }

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
     * Set titleTranslated
     *
     * @param string $titleTranslated
     * @return Article
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
     * Set abstractTranslated
     *
     * @param string $abstractTranslated
     * @return Article
     */
    public function setAbstractTranslated($abstractTranslated) {
        $this->abstractTranslated = $abstractTranslated;
        return $this;
    }

    /**
     * Get abstractTranslated
     *
     * @return string
     */
    public function getAbstractTranslated() {
        return $this->abstractTranslated;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subjects;

    /**
     * Add subjects
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subjects
     * @return Article
     */
    public function addSubject(\Ojstr\JournalBundle\Entity\Subject $subjects) {
        $this->subjects[] = $subjects;

        return $this;
    }

    /**
     * Remove subjects
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subjects
     */
    public function removeSubject(\Ojstr\JournalBundle\Entity\Subject $subjects) {
        $this->subjects->removeElement($subjects);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects() {
        return $this->subjects;
    }

    /**
     * @var \Ojstr\JournalBundle\Entity\Journal
     */
    private $journal;

    /**
     * Set journal
     *
     * @param \Ojstr\JournalBundle\Entity\Journal $journal
     * @return Article
     */
    public function setJournal(\Ojstr\JournalBundle\Entity\Journal $journal = null) {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return \Ojstr\JournalBundle\Entity\Journal
     */
    public function getJournal() {
        return $this->journal;
    }

    /**
     * Set subjects
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subjects
     * @return Article
     */
    public function setSubjects(\Ojstr\JournalBundle\Entity\Subject $subjects = null) {
        $this->subjects = $subjects;

        return $this;
    }

}
