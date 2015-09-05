<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\AnalyticsBundle\Entity\IssueStatistic;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\CoreBundle\Annotation\Display as Display;

/**
 * Issue
 * @GRID\Source(columns="id,journal.title,volume,number,title,year,datePublished")
 * @ExclusionPolicy("all")
 */
class Issue extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\IssueTranslation")
     */
    protected $translations;
    /**
     * @var integer
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $journalId;
    /**
     *
     * @var Journal
     * @Groups({"IssueDetail"})
     */
    private $journal;
    /**
     * @var string
     * @GRID\Column(title="volume")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $volume;
    /**
     * @var string
     * @GRID\Column(title="number")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $number;
    /**
     * @var string
     * @GRID\Column(title="title")
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $title;
    /**
     * @var string
     *             cover image path
     * @Expose
     * @Groups({"IssueDetail"})
     * @Display\Image(filter="issue_cover")
     */
    private $cover;
    /**
     * @var boolean
     * @GRID\Column(title="special")
     * @Expose
     * @Groups({"IssueDetail"})
     */
    private $special;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $description;
    /**
     * @var string
     * @GRID\Column(title="year")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $year;
    /**
     * @var \DateTime
     * @GRID\Column(title="publishdate")
     * @Expose
     * @Groups({"IssueDetail"})
     */
    private $datePublished;
    /**
     * @var Collection
     * @Groups({"IssueDetail","JournalDetail"})
     */
    private $articles;
    /**
     * @var string
     * @Expose
     * @Groups({"IssueDetail"})
     * @Display\Image(filter="issue_header")
     */
    private $header;
    /**
     * @var Collection
     * @Groups({"IssueDetail"})
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
    /** @var  boolean */
    private $published = false;
    /**
     * @var boolean
     * @Expose
     * @Groups({"IssueDetail"})
     */
    private $supplement;
    /**
     * @var string
     * @Expose
     * @Groups({"IssueDetail"})
     * @Display\File(path="issuefiles")
     */
    private $full_file;
    /**
     * @var Collection|IssueFile[]
     * @Expose
     */
    private $issueFiles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->issueFiles = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->statistics = new ArrayCollection();
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
    public function setJournal($journal)
    {
        $this->journal = $journal;

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
        return $this->translate()->getDescription();
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
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
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
     * @return $this
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
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
     * Add section to issue
     * @param  JournalSection $section
     * @return $this
     */
    public function addSection(JournalSection $section)
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * Remove section from issue
     *
     * @param JournalSection $section
     */
    public function removeSection(JournalSection $section)
    {
        $this->articles->removeElement($section);
    }

    /**
     * Get sections
     *
     * @return Collection
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
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
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
        return $this->full_file;
    }

    /**
     * @param  string $full_file
     * @return $this
     */
    public function setFullFile($full_file)
    {
        $this->full_file = $full_file;

        return $this;
    }

    /**
     * @return array|Collection|IssueFile[]
     */
    public function getIssueFiles()
    {
        return $this->issueFiles;
    }

    /**
     * @param array|Collection|IssueFile[] $issueFiles
     * @return $this
     */
    public function setIssueFiles($issueFiles)
    {
        $this->issueFiles = $issueFiles;
        return $this;
    }

    /**
     * @param IssueFile $issueFile
     * @return $this
     */
    public function addIssueFile(IssueFile $issueFile)
    {
        $this->issueFiles->add($issueFile);
        return $this;
    }

    /**
     * @param IssueFile $issueFile
     */
    public function removeIssueFile(IssueFile $issueFile)
    {
        $this->issueFiles->removeElement($issueFile);
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
     * @return ArrayCollection|\Ojs\AnalyticsBundle\Entity\IssueStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|\Ojs\AnalyticsBundle\Entity\IssueStatistic[] $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * Returns the article's view count
     *
     * @return int
     */
    public function getViewCount()
    {
        $count = 0;

        foreach ($this->statistics as $stat) {
            $count += $stat->getView();
        }

        return $count;
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
     */
    public function setPublicURI($publicURI)
    {
        $this->publicURI = $publicURI;
    }
}
