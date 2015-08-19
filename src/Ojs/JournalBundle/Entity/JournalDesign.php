<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * JournalDesign
 * @GRID\Source(columns="id,journal.title,title")
 */
class JournalDesign
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

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
     * @var string
     * @GRID\Column(title="editableContent")
     */
    private $editableContent;

    /**
     * @var boolean
     * @GRID\Column(title="basedesign")
     */
    private $isPublic;

    /**
     *
     * @var Journal
     */
    private $journal;

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
     * Set journal
     * @param  Journal      $journal
     * @return JournalTheme
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return boolean
     */
    public function isIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param boolean $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getEditableContent()
    {
        return $this->editableContent;
    }

    /**
     * @param string $editableContent
     */
    public function setEditableContent($editableContent)
    {
        $this->editableContent = $editableContent;
    }


}
