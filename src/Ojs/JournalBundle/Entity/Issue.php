<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Ojs\AnalyticsBundle\Entity\IssueStatistic;
use Ojs\CoreBundle\Annotation\Display;
use Ojs\CoreBundle\Entity\AnalyticsTrait;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\CoreBundle\Params\IssueDisplayModes;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Issue
 * @GRID\Source(columns="id,volume,number,translations.title,year,datePublished,lastIssue")
 * @GRID\Source(columns="id, translations.title, number, volume", groups={"export"})
 * @JMS\ExclusionPolicy("all")
 */
class Issue extends AbstractTranslatable implements JournalItemInterface
{
    use GenericEntityTrait;
    use AnalyticsTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     * @JMS\Expose
     */
    protected $id;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\IssueTranslation")
     * @JMS\Expose
     *
     */
    protected $translations;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var string
     * @GRID\Column(title="volume")
     * @JMS\Expose
     */
    private $volume;

    /**
     * @var string
     * @GRID\Column(title="number")
     * @JMS\Expose
     */
    private $number;

    /**
     * @var string
     * @GRID\Column(title="title", field="translations.title", safe=false)
     */
    private $title;

    /**
     * @var int
     */
    private $totalArticleView = 0;

    /**
     * @var int
     */
    private $totalArticleDownload = 0;

    /**
     * @var string cover image path
     * @JMS\Expose
     * @Display\Image(filter="issue_cover")
     */
    private $cover;

    /**
     * @var boolean
     * @GRID\Column(title="special")
     * @JMS\Expose
     */
    private $special = false;

    /**
     * @var boolean
     * @GRID\Column(title="last.issue")
     */
    private $lastIssue = false;

    /**
     * @var string
     * @JMS\Expose
     */
    private $description;

    /**
     * @var string
     * @GRID\Column(title="year")
     * @JMS\Expose
     */
    private $year;

    /**
     * @var \DateTime
     * @GRID\Column(title="publishdate")
     * @JMS\Expose
     */
    private $datePublished;

    /**
     * @var ArrayCollection|Article[]
     * @JMS\Expose
     */
    private $articles;

    /**
     * @var string
     * @JMS\Expose
     * @Display\Image(filter="issue_header")
     */
    private $header;

    /**
     * @var ArrayCollection|Section[]
     */
    private $sections;

    /**
     * @var ArrayCollection|IssueStatistic[]
     */
    private $statistics;

    /**
     * @var string
     */
    private $publicURI;

    /**
     * @var  bool
     */
    private $published = false;

    /**
     * @var  bool
     */
    private $public = false;

    /**
     * @var boolean
     * @JMS\Expose
     */
    private $supplement = false;

    /**
     * @var string
     * @JMS\Expose
     * @Display\File(path="issuefiles")
     */
    private $fullFile;

    /**
     * @var ArrayCollection|IssueFile[]
     * @JMS\Expose
     */
    private $issueFiles;

    /**
     * @var integer
     */
    private $numerator;

    /**
     * @var integer
     */
    private $displayMode = IssueDisplayModes::SHOW_ALL;

    /**
     * @var integer
     */
    private $inPress = false;

    /**
     * @var ArrayCollection[Catalog]
     */
    private $catalogs;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->issueFiles = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->statistics = new ArrayCollection();
        $this->catalogs = new ArrayCollection();
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
     * @param  Journal $journal
     * @return Issue
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

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
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
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
     * Get cover image path
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
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
        return (bool)$this->special;
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getLogicalFieldTranslation('description', false);
    }

    /**
     * Set description
     *
     * @param  string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\IssueTranslation
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
            $translation = new IssueTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setDescription($defaultTranslation->getDescription());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year
     *
     * @param  \DateTime $year
     * @return Issue
     */
    public function setYear($year)
    {
        $this->year = $year;

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
     * Add article
     *
     * @param  Article $article
     * @return Issue
     */
    public function addArticle(Article $article)
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setIssue($this);
        }

        return $this;
    }

    /**
     * Remove article
     *
     * @param Article $article
     * @return Issue
     */
    public function removeArticle(Article $article)
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->setIssue(null);
        }
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
     * Add section to issue
     * @param  Section $section
     * @return $this
     */
    public function addSection(Section $section)
    {

        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove section from issue
     *
     * @param Section $section
     */
    public function removeSection(Section $section)
    {
        $this->sections->removeElement($section);
    }

    /**
     * Get sections
     *
     * @return ArrayCollection|Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param  string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Return formatted issue title and id eg. :  "Issue title [#id]"
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle()."[#{$this->getId()}]";
    }

    /**
     * Get title
     * @param bool $withLocale
     * @return string
     */
    public function getTitle($withLocale = false)
    {
        return $this->getLogicalFieldTranslation('title', $withLocale);
    }

    /**
     * Get title translations
     *
     * @return string
     */
    public function getTitleTranslations()
    {
        $titles = [];
        /** @var IssueTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

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
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param  boolean $published
     * @return $this
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param  boolean $public
     * @return $this
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSupplement()
    {
        return $this->supplement;
    }

    /**
     * Get supplement
     *
     * @return boolean
     */
    public function getSupplement()
    {
        return $this->supplement;
    }

    /**
     * @param  boolean $supplement
     * @return $this
     */
    public function setSupplement($supplement)
    {
        $this->supplement = $supplement;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullFile()
    {
        return $this->fullFile;
    }

    /**
     * @param  string $fullFile
     * @return $this
     */
    public function setFullFile($fullFile)
    {
        $this->fullFile = $fullFile;

        return $this;
    }

    /**
     * @return ArrayCollection|IssueFile[]
     */
    public function getIssueFiles()
    {
        return $this->issueFiles;
    }

    /**
     * @param IssueFile $issueFile
     * @return $this
     */
    public function addIssueFile(IssueFile $issueFile)
    {
        if(!$this->issueFiles->contains($issueFile)){
            $this->issueFiles->add($issueFile);
            $issueFile->setIssue($this);
        }

        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Issue
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
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return ArrayCollection|IssueStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|IssueStatistic[] $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return string
     */
    public function getPublicURI()
    {
        return $this->publicURI;
    }

    /**
     * @param string $publicURI
     *
     * @return $this
     */
    public function setPublicURI($publicURI)
    {
        $this->publicURI = $publicURI;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumerator()
    {
        return $this->numerator;
    }

    /**
     * @param int $numerator
     *
     * @return $this
     */
    public function setNumerator($numerator)
    {
        $this->numerator = $numerator;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isLastIssue()
    {
        return $this->lastIssue;
    }

    /**
     * @param boolean $lastIssue
     *
     * @return $this
     */
    public function setLastIssue($lastIssue)
    {
        $this->lastIssue = $lastIssue;

        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayMode()
    {
        return $this->displayMode;
    }

    /**
     * @param int $displayMode
     * @return Issue
     */
    public function setDisplayMode($displayMode)
    {
        $this->displayMode = $displayMode;

        return $this;
    }

    /**
     * @return int
     */
    public function getInPress()
    {
        return $this->inPress;
    }

    /**
     * @param int $inPress
     * @return Issue
     */
    public function setInPress($inPress)
    {
        $this->inPress = $inPress;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalArticleView()
    {
        return $this->totalArticleView;
    }

    /**
     * @param int $totalArticleView
     *
     * @return $this
     */
    public function setTotalArticleView($totalArticleView)
    {
        $this->totalArticleView = $totalArticleView;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalArticleDownload()
    {
        return $this->totalArticleDownload;
    }

    /**
     * @param int $totalArticleDownload
     *
     * @return $this
     */
    public function setTotalArticleDownload($totalArticleDownload)
    {
        $this->totalArticleDownload = $totalArticleDownload;

        return $this;
    }

    /**
     * Add catalog
     *
     * @param \Ojs\JournalBundle\Entity\Catalog $catalog
     *
     * @return Issue
     */
    public function addCatalog(\Ojs\JournalBundle\Entity\Catalog $catalog)
    {
        $this->catalogs[] = $catalog;

        return $this;
    }

    /**
     * Remove catalog
     *
     * @param \Ojs\JournalBundle\Entity\Catalog $catalog
     */
    public function removeCatalog(\Ojs\JournalBundle\Entity\Catalog $catalog)
    {
        $this->catalogs->removeElement($catalog);
    }

    /**
     * Get catalogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCatalogs()
    {
        return $this->catalogs;
    }
}
