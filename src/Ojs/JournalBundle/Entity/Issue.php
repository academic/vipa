<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\JournalBundle\Entity\IssueFile;

/**
 * Issue
 * @GRID\Source(columns="id,journal.title,volume,number,title,year,datePublished")
 * @ExclusionPolicy("all")
 */
class Issue implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $id;

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
     */
    private $header;

    /**
     * @var Collection
     * @Groups({"IssueDetail"})
     */
    private $sections;

    protected $translations;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->issueFiles = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(IssueTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function setTranslations($translations)
    {
        foreach($translations as $translation){
            $this->addTranslation($translation);
        }
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
     * Get journal
     *
     * @return Journal
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
     * @var string
     */
    protected $header_options;
    /** @var  string */
    protected $cover_options;

    /**
     * @return string
     */
    public function getCoverOptions()
    {
        return $this->cover_options;
    }

    /**
     * @param string $cover_options
     */
    public function setCoverOptions($cover_options)
    {
        $this->cover_options = $cover_options;
    }

    /**
     * @return string
     */
    public function getHeaderOptions()
    {
        return $this->header_options;
    }

    /**
     * @param string $header_options
     */
    public function setHeaderOptions($header_options)
    {
        $this->header_options = $header_options;
    }

    /** @var  boolean */
    private $published = false;

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
     * @var boolean
     * @Expose
     * @Groups({"IssueDetail"})
     */
    private $supplement;

    /**
     * @return boolean
     */
    public function isSupplement()
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
     * @var string
     * @Expose
     * @Groups({"IssueDetail"})
     */
    private $full_file;

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
     * @var Collection|IssueFile[]
     * @Expose
     */
    private $issueFiles;

    /**
     * @return array|Collection|IssueFile[]
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
     * @param array|Collection|IssueFile[] $issueFiles
     * @return $this
     */
    public function setIssueFiles($issueFiles)
    {
        $this->issueFiles = $issueFiles;
        return $this;
    }
}
