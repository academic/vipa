<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * JournalDesign
 * @GRID\Source(columns="id,title,design.title,isPublic")
 */
class JournalDesign implements Translatable
{
    use GenericEntityTrait;

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
     * @GRID\Column(title="ojs.is_public",field="isPublic")
     */
    private $isPublic;

    /**
     *
     * @var Journal
     */
    private $journal;

    /**
     *
     * @var Design
     * @GRID\Column(title="design",field="design.title")
     */
    private $design;

    /**
     *
     * @var string Title
     * @GRID\Column(title="title",field="title")
     */
    private $title;

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
     * @param  integer $journalId
     * @return $this
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
     * @param  integer $designId
     * @return $this
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
     * @param  Journal $journal
     * @return $this
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
     * @param  Design $design
     * @return $this
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
     * @param  string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isPublic
     *
     * @param  string $isPublic
     * @return $this
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return integer
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }
}
