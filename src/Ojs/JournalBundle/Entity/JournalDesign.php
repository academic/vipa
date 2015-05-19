<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\Common\Entity\GenericExtendedEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * JournalDesign
 * @GRID\Source(columns="id,journal.title,design.title")
 */
class JournalDesign extends GenericExtendedEntity
{

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var integer
     */
    private $designId;

    /**
     * @var integer
     */
    private $isPublic;

    /**
     *
     * @var Journal
     * @GRID\Column(title="journal",field="journal.title")
     */
    private $journal;

    /**
     *
     * @var Design
     * @GRID\Column(title="design",field="design")
     */
    private $design;

    /**
     *
     * @var Title
     * @GRID\Column(title="design",field="design.title")
     */
    private $title;

    public function __construct()
    {
        parent::__construct();
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
     * Set journalId
     *
     * @param integer $journalId
     * @return JournalDesign
     */
    public function setJournalId($journalId)
    {
        $this->journalId = $journalId;

        return $this;
    }

    /**
     * Get journalId
     *
     * @return integer 
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * Set designId
     *
     * @param integer $designId
     * @return JournalDesign
     */
    public function setDesignId($designId)
    {
        $this->designId = $designId;

        return $this;
    }

    /**
     * Get designId
     *
     * @return integer 
     */
    public function getDesignId()
    {
        return $this->designId;
    }

    
    /**
     * Set journal
     * @param Journal $journal
     * @return JournalDesign
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * Get journal
     *
     * @return Journal 
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set design
     *
     * @param Design $design
     * @return JournalDesign
     */
    public function setDesign($design)
    {
        $this->design = $design;
        return $this;
    }

    /**
     * Get design
     *
     * @return Design 
     */
    public function getDesign()
    {
        return $this->design;
    }
    /**
     * Set title
     *
     * @param Title $title
     * @return JournalDesign
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isPublic
     *
     * @param isPublic $isPublic
     * @return JournalDesign
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * Get isPublic
     *
     * @return isPublic
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }
}
