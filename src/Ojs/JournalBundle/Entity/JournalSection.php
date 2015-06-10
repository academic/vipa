<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * JournalSection
 * @GRID\Source(columns="id,title,allowIndex,hideTitle,journal.title")
 */
class JournalSection implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @GRID\Column(title="journalsection.title")
     */
    private $title;

    /**
     * @var boolean
     * @GRID\Column(title="journalsection.allow_index")
     */
    private $allowIndex = true;

    /**
     * @var boolean
     * @GRID\Column(title="journalsection.hide_title")
     */
    private $hideTitle = false;

    /**
     * @var integer
     */
    private $journalId;

    /**
     * @var Collection|Article[]
     */
    private $articles;

    /**
     * @var Journal
     * @GRID\Column(title="journalsection.journal", field="journal.title")
     */
    private $journal;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
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
     * Add articles
     *
     * @param  Article $article
     * @return $this
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set title
     *
     * @param  string         $title
     * @return JournalSection
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
     * Set allowIndex
     *
     * @param  boolean        $allowIndex
     * @return JournalSection
     */
    public function setAllowIndex($allowIndex)
    {
        $this->allowIndex = $allowIndex;

        return $this;
    }

    /**
     * Get allowIndex
     *
     * @return boolean
     */
    public function getAllowIndex()
    {
        return $this->allowIndex;
    }

    /**
     * Set hideTitle
     *
     * @param  boolean        $hideTitle
     * @return JournalSection
     */
    public function setHideTitle($hideTitle)
    {
        $this->hideTitle = $hideTitle;

        return $this;
    }

    /**
     * Get hideTitle
     *
     * @return boolean
     */
    public function getHideTitle()
    {
        return $this->hideTitle;
    }

    /**
     * Set journalId
     *
     * @param  integer        $journalId
     * @return JournalSection
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
     * Set journal
     *
     * @param  Journal        $journal
     * @return JournalSection
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * Get journal
     *
     * @return integer
     */
    public function getJournal()
    {
        return $this->journal;
    }
}
