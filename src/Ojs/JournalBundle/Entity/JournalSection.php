<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\JournalBundle\Entity\ArticleTypesTranslation;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * JournalSection
 * @GRID\Source(columns="id,title,allowIndex,hideTitle,journal")
 */
class JournalSection extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    protected $id;

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
     * @GRID\Column(title="journal")
     */
    private $journal;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalSectionTranslation")
     */
    protected $translations;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalSectionTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }
        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }
        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new JournalSectionTranslation();
            if(!is_null($defaultTranslation)){
                $translation->setTitle($defaultTranslation->getTitle());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;
        return $translation;
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
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
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
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }
}
