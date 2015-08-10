<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * Design
 * @GRID\Source(columns="id,name,title,isPublic")
 */
class Design implements Translatable
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
     * @var ArrayCollection|Journal[]
     */
    private $journals;

    public function __construct()
    {
        $this->journals = new ArrayCollection();
    }

    /**
     * Add journal
     * @param  Journal $journal
     * @return $this
     */
    public function addJournal(Journal $journal)
    {
        if(!$this->journals->contains($journal)){
            $this->journals->add($journal);
            $journal->addDesign($this);
        }

        return $this;
    }

    /**
     * Remove journal
     * @param Journal $journal
     */
    public function removeJournal(Journal $journal)
    {
        if($this->journals->contains($journal)){
            $this->journals->removeElement($journal);
            $journal->removeDesign($this);
        }
    }

    /**
     * Get journals
     * @return ArrayCollection|Journal[]
     */
    public function getJournals()
    {
        return $this->journals;
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * Set editableContent
     * @param  string $editableContent
     * @return $this
     */
    public function setEditableContent($editableContent)
    {
        $this->editableContent = $editableContent;

        return $this;
    }

    /**
     * Get editableContent
     * @return string
     */
    public function getEditableContent()
    {
        return $this->editableContent;
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

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Design
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Design
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
