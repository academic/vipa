<?php

namespace Ojs\JournalBundle\Entity;

use \Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Design
 * @GRID\Source(columns="id,name,title,isPublic")
 */
class Design extends GenericExtendedEntity
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
     * @var string
     * @GRID\Column(title="content")
     */
    private $content;

    /**
     * @var boolean
     * @GRID\Column(title="basedesign")
     */
    private $isPublic;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journalDesigns;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals;

    public function __construct()
    {
        parent::__construct();
        $this->journalDesigns = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getJournalDesigns()
    {
        return $this->journalDesigns;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $journalDesigns
     * @return Design
     */
    public function setJournalDesigns(\Doctrine\Common\Collections\Collection $journalDesigns)
    {
        $this->journalDesigns = $journalDesigns;
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
     * @return Design
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
     * @return Design
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
     * Set content
     * @param  string $content
     * @return Design
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set isPublic 
     * @param  boolean $isPublic
     * @return Design
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
