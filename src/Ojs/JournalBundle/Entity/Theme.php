<?php

namespace Ojs\JournalBundle\Entity;

use \Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Theme
 * @GRID\Source(columns="id,name,title,isPublic")
 */
class Theme extends GenericExtendedEntity
{

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="name")
     */
    private $name;

    /**
     * @var string
     * @GRID\Column(title="content")
     */
    private $title;

    /**
     * @var boolean
     * @GRID\Column(title="basetheme")
     */
    private $isPublic;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journalThemes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals;

    public function __construct()
    {
        parent::__construct();
        $this->journalThemes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->journals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add journal
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return Language
     */
    public function addJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journals[] = $journal;
        return $this;
    }


    /**
     * Remove journal
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     */
    public function removeJournal(\Ojs\JournalBundle\Entity\Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournalThemes()
    {
        return $this->journalThemes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $journalThemes
     * @return Theme
     */
    public function setJournalThemes(\Doctrine\Common\Collections\Collection $journalThemes)
    {
        $this->journalThemes = $journalThemes;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name 
     * @param  string $name
     * @return Theme
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     * @param  string $title
     * @return Theme
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isPublic 
     * @param  boolean $isPublic
     * @return Theme
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * Get isPublic
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

}
