<?php

namespace Ojstr\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Article
 * @ExclusionPolicy("all")
 */
class Article extends \Ojstr\Common\Entity\GenericExtendedEntity {

    /**
     * auto-incremented article unique id
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var integer
     * @Expose
     */
    private $status;

    /**
     * user id of the owner of this article
     * @var integer
     * @Expose 
     */
    private $submitterId;

    /**
     * (optional)
     * @var string
     * @Expose
     */
    private $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     * @Expose
     */
    private $otherId;

    /**
     * @var integer
     * @Expose
     */
    private $journalId;

    /**
     * Original article title
     * @var string
     * @Expose
     */
    private $title;

    /**
     * Roman transliterated title
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
    private $keywords;

    /**
     * Some artilce carries no authorship
     * @var boolean
     * @Expose
     */
    private $isAnonymous;

    /**
     * @var \DateTime
     * @Expose
     */
    private $pubdate;

    /**
     * @var string
     * @Expose
     */
    private $pubdateSeason;

    /**
     * @var string
     * @Expose
     */
    private $part;

    /**
     * @var integer
     * @Expose
     */
    private $firstPage;

    /**
     * @var integer
     * @Expose
     */
    private $lastPage;

    /**
     * @var string
     * @Expose
     */
    private $uri;

    /**
     * Original abstract
     * @var string
     * @Expose
     */
    private $abstract;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $subjects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $citations;

    /**
     * @var \Ojstr\JournalBundle\Entity\Journal
     * @Expose
     */
    private $journal;

    /**
     * Constructor
     */
    public function __construct() {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->citations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subject
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subject
     * @return Article
     */
    public function addSubject(\Ojstr\JournalBundle\Entity\Subject $subject) {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param \Ojstr\JournalBundle\Entity\Subject $subject
     */
    public function removeSubject(\Ojstr\JournalBundle\Entity\Subject $subject) {
        $this->subjects->removeElement($subject);
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
     * Add citation
     *
     * @param \Ojstr\JournalBundle\Entity\Citation $citation
     * @return Article
     */
    public function addCitation(\Ojstr\JournalBundle\Entity\Citation $citation) {
        $this->citations[] = $citation;
        return $this;
    }

    /**
     * Remove citation
     *
     * @param \Ojstr\JournalBundle\Entity\Citation $citation
     */
    public function removeCitation(\Ojstr\JournalBundle\Entity\Citation $citation) {
        $this->citations->removeElement($citation);
    }

    /**
     * Get citations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCitations() {
        return $this->citations;
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
     * 
     * @return string
     */
    public function getStatusText() {
        return \Ojstr\Common\Params\ArticleParams::statusText($this->status);
    }

    /**
     * 
     * @return string
     */
    public function getStatusColor() {
        return \Ojstr\Common\Params\ArticleParams::statusColor($this->status);
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $status;
    }

    public function getSubmitterId() {
        return $this->submitterId;
    }

    public function setSubmitterId($submitterId) {
        $this->submitterId = $submitterId;
        return $submitterId;
    }

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
