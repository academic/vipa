<?php

namespace Ojstr\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Article
 * @ExclusionPolicy("all")
 */
class Article extends Model\ArticleModel {

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

    public function getStatus() {
        return $this->status;
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

    public function setStatus($status) {
        $this->status = $status;
        return $status;
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

}
