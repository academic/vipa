<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Vipa\CoreBundle\Annotation\Display;

/**
 * Design
 * @GRID\Source(columns="id,owner,title,public")
 */
class Design
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * @var string
     * @Display\Exclude
     */
    private $content;

    /**
     * @var string
     * @Display\Exclude
     */
    private $editableContent;

    /**
     * @var boolean
     */
    private $public = true;

    /**
     *
     * @var Journal
     * @GRID\Column(title="journal")
     */
    private $owner;

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
     * Get owner
     *
     * @return Journal
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set journal
     * @param  Journal      $owner
     * @return Design
     */
    public function setOwner(Journal $owner = null)
    {
        $this->owner = $owner;

        return $this;
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
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
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
