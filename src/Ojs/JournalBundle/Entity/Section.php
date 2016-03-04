<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Section
 * @GRID\Source(columns="id,translations.title,allowIndex,hideTitle")
 */
class Section extends AbstractTranslatable implements JournalItemInterface
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="ID")
     */
    protected $id;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\SectionTranslation")
     */
    protected $translations;

    /**
     * @var string
     * @GRID\Column(title="section.title", field="translations.title",safe=false)
     */
    private $title;

    /**
     * @var boolean
     * @GRID\Column(title="section.allow_index")
     */
    private $allowIndex = true;

    /**
     * @var boolean
     * @GRID\Column(title="section.hide_title")
     */
    private $hideTitle = false;

    /**
     * @var ArrayCollection|Article[]
     */
    private $articles;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var int
     */
    private $sectionOrder = 1;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->translations = new ArrayCollection();
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
     * @return ArrayCollection|Article[]
     */
    public function getArticles()
    {
        return $this->articles;
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
     * Set allowIndex
     *
     * @param  boolean        $allowIndex
     * @return Section
     */
    public function setAllowIndex($allowIndex)
    {
        $this->allowIndex = $allowIndex;

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
     * Set hideTitle
     *
     * @param  boolean        $hideTitle
     * @return Section
     */
    public function setHideTitle($hideTitle)
    {
        $this->hideTitle = $hideTitle;

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
     * Set journal
     *
     * @param  Journal        $journal
     * @return Section
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    public function __toString()
    {
        if (!is_string($this->getTitle()) || empty($this->getTitle())) {
            return $this->translations->first()->getTitle();
        } else {
            return $this->getTitle();
        }
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
     * Get title translations
     *
     * @return string
     */
    public function getTitleTranslations()
    {
        $titles = [];
        /** @var SectionTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Section
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\SectionTranslation
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
            $translation = new SectionTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * @return int
     */
    public function getSectionOrder()
    {
        return $this->sectionOrder;
    }

    /**
     * @param int $sectionOrder
     *
     * @return $this
     */
    public function setSectionOrder($sectionOrder)
    {
        $this->sectionOrder = $sectionOrder;

        return $this;
    }
}
