<?php

namespace Ojs\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Subject
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,subject,description")
 */
class Subject extends \Ojs\Common\Entity\GenericExtendedEntity {

    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    private $id;
    private $lft;
    private $lvl;
    private $rgt;
    private $root;
    
    /*
     * @var Subject
     * @Expose
     * @GRID\Column(title="parent")
     */
    private $parent;
    private $children;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="subject")
     */
    private $subject;

    /**
     * @var string
     * @Expose
     * @GRID\Column(title="description")
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * This data will be pre-calculated with scheduled tasks
     * @var int
     */
    private $totalJournalCount;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->journas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setParent(Subject $parent = null) {
        $this->parent = $parent;
    }

    /**
     * 
     * @return Subject
     */
    public function getParent() {
        return $this->parent;
    }

    public function getChildren() {
        return $this->children;
    }

    public function getRoot() {
        return $this->root;
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
     * Set subject
     *
     * @param  string  $subject
     * @return Subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Set totalJournalCount
     * @param  integer  $totalJournalCount
     * @return Subject
     */
    public function setTotalJournalCount($totalJournalCount) {
        $this->totalJournalCount = $totalJournalCount;
        return $this;
    }

    /**
     * Get totalJournalCount
     * @return integer
     */
    public function getTotalJournalCount() {
        return $this->totalJournalCount;
    }

    /**
     * Set description
     *
     * @param  string  $description
     * @return Subject
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Add users
     *
     * @param  \Ojs\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Ojs\UserBundle\Entity\User $users) {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Ojs\UserBundle\Entity\User $users
     */
    public function removeUser(\Ojs\UserBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Get subjects
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals() {
        return $this->journals;
    }

    /**
     * Add subject
     *
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return Subject
     */
    public function addJournal(\Ojs\UserBundle\Entity\User $journal) {
        $this->journals[] = $journal;
        return $this;
    }

    /**
     * Remove journal
     *
     * @param \Ojs\UserBundle\Entity\User $journal
     */
    public function removeJournal(\Ojs\JournalBundle\Entity\Journal $journal) {
        $this->journals->removeElement($journal);
    }

    public function hasJournals() {
        $totalJournals = $this->journals->count();
        return $totalJournals > 0;
    }

    private $slug;

    /**
     * @return mixed
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return $this
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    public function __toString() {
        return $this->subject;
    }

}
