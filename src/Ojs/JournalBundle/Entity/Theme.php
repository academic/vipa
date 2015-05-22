<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Theme
 * @GRID\Source(columns="id,name,title,isPublic")
 */
class Theme implements Translatable
{
    use GenericEntityTrait;
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
     * @var Collection
     */
    private $journalThemes;

    /**
     * @var Collection
     */
    private $journals;

    public function __construct()
    {
        $this->journalThemes = new ArrayCollection();
        $this->journals = new ArrayCollection();
    }

    /**
     * Add journal
     * @param  Journal $journal
     * @return $this
     */
    public function addJournal(Journal $journal)
    {
        $this->journals[] = $journal;

        return $this;
    }

    /**
     * Remove journal
     * @param Journal $journal
     */
    public function removeJournal(Journal $journal)
    {
        $this->journals->removeElement($journal);
    }

    /**
     * Get journals
     * @return Collection
     */
    public function getJournals()
    {
        return $this->journals;
    }

    /**
     * @return Collection
     */
    public function getJournalThemes()
    {
        return $this->journalThemes;
    }

    /**
     * @param  Collection $journalThemes
     * @return Theme
     */
    public function setJournalThemes(Collection $journalThemes)
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
