<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Vipa\CoreBundle\Annotation\Display as Display;

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
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * @var string
     * @GRID\Column(title="content")
     * @Display\Exclude
     */
    private $content;

    /**
     * @var string
     * @GRID\Column(title="editableContent")
     * @Display\Exclude
     */
    private $editableContent;

    /**
     * @var boolean
     * @GRID\Column(title="basedesign")
     */
    private $public = true;

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
     * Get publisher
     *
     * @return Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
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
