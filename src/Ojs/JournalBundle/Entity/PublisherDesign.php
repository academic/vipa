<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\Common\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * PublisherDesign
 * @GRID\Source(columns="id,journal.title,title")
 */
class PublisherDesign
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
     * @var Publisher
     */
    private $publisher;

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
     * Set publisher
     * @param  Publisher $publisher
     * @return PublisherTheme
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
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
