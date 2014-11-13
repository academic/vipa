<?php

namespace Ojs\JournalBundle\Entity;

/**
 * Issue
 */
class Issue extends \Ojs\Common\Entity\GenericExtendedEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $journalId;

    /**
     *
     * @var Journal
     */
    private $journal;

    /**
     * @var string
     */
    private $volume;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     * cover image path
     */
    private $cover;

    /**
     * @var boolean
     */
    private $special;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $year;

    /**
     * @var \DateTime
     */
    private $datePublished;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

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
     * @param  \Ojs\JournalBundle\Entity\Journal $journal
     * @return \Ojs\JournalBundle\Entity\Article
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return \Ojs\JournalBundle\Entity\Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set journalId
     *
     * @param  integer $journalId
     * @return Issue
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
     * Set volume
     *
     * @param  string $volume
     * @return Issue
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set number
     *
     * @param  string $number
     * @return Issue
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set cover image path
     *
     * @param  string $cover
     * @return Issue
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover image path
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set is special
     *
     * @param  boolean $special
     * @return Issue
     */
    public function setSpecial($special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * is special
     *
     * @return boolean
     */
    public function getSpecial()
    {
        return $this->special;
    }

    public function isSpecial()
    {
        return (bool) $this->special;
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set year
     *
     * @param  string $year
     * @return Issue
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set datePublished
     *
     * @param  \DateTime $datePublished
     * @return Issue
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    /**
     * Get datePublished
     *
     * @return \DateTime
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * Add article
     *
     * @param  \Ojs\JournalBundle\Entity\Article $article
     * @return Language
     */
    public function addArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Ojs\JournalBundle\Entity\Article $article
     */
    public function removeArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

}
