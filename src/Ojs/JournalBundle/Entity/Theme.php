<?php

namespace Ojs\JournalBundle\Entity;

use \Ojs\Common\Entity\GenericExtendedEntity;

/**
 * Theme
 */
class Theme extends GenericExtendedEntity
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var boolean
     */
    private $isPublic;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journalThemes;

    public function __construct()
    {
        $this->journalThemes = new \Doctrine\Common\Collections\ArrayCollection();
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
