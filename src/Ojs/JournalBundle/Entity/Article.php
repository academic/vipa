<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\AnalyticsBundle\Entity\ArticleStatistic;
use Ojs\CoreBundle\Entity as CommonTraits;
use Ojs\CoreBundle\Entity\AnalyticsTrait;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\CoreBundle\Params\DoiStatuses;
use Ojs\UserBundle\Entity\User;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\CoreBundle\Params\ArticleStatuses;

/**
 * Article
 * @GRID\Source(columns="id, numerator, translations.title, issue.translations.title, journal.title, pubdate, status, section.title, doiStatus")
 * @GRID\Source(columns="id, numerator, status, translations.title, journal.title", groups={"submission"})
 * @GRID\Source(columns="id, translations.title", groups={"export"})
 * @ExclusionPolicy("all")
 */
class Article extends AbstractTranslatable implements JournalItemInterface
{
    use GenericEntityTrait;
    use AnalyticsTrait;

    /**
     * auto-incremented article unique id
     * @GRID\Column(title="id")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    protected $id;
    /**
     * (optional) English transliterated abstract
     * @var string
     */
    protected $abstractTransliterated;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\ArticleTranslation")
     * @Expose
     */
    protected $translations;
    /**
     * @var integer
     * @Expose
     * @GRID\Column(type="text", groups={"submission"})
     * @Grid\Column(field="status", title="status", filter="select", selectFrom="values", values={
     *     "-4"="status.withdrawn",
     *     "-3"="status.rejected",
     *     "-2"="status.unpublished",
     *     "-1"="status.not_submitted",
     *     "0"="status.inreview",
     *     "1"="status.published"
     * })
     * @Expose
     */
    private $status;
    /**
     * (optional)
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $doi;

    /**
     * @var \DateTime
     */
    private $doiRequestTime;

    /**
     * Could contain any article ID used by the provider
     * @var string
     */
    private $otherId;
    /**
     * Original article title
     * @var string
     * @GRID\Column(title="title", field="translations.title", safe=false)
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $title;
    /**
     * Roman transliterated title
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $titleTransliterated;
    /**
     * @var string
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $keywords;
    /**
     * Some articles carries no authorship
     * @var boolean
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $anonymous = false;
    /**
     * @var \DateTime
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $submissionDate;
    /**
     * @var \DateTime
     * @Expose
     */
    private $acceptanceDate;
    /**
     * @var \DateTime
     * @GRID\Column(title="pubdate")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $pubdate;
    /**
     * @var string
     * @Expose
     */
    private $pubdateSeason;
    /**
     * @var integer
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $firstPage;
    /**
     * @var integer
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $lastPage;
    /**
     * @var string
     */
    private $uri;
    /**
     *
     * @var string
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $primaryLanguage;
    /**
     *
     * @var integer
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $orderNum;
    /**
     * Original abstract
     * @var string
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $abstract;
    /**
     * @var Collection
     * @JMS\Expose
     */
    private $subjects;
    /**
     * @var Collection|Lang[]
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $languages;
    /**
     * @var Issue
     * @GRID\Column(title="issue Title", field="issue.translations.title", safe=false)
     */
    private $issue;
    /**
     * @var ArticleTypes
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $articleType;
    /**
     * @var Collection|Citation[]
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $citations;
    /**
     * @var Journal
     */
    private $journal;
    /**
     * @var Section
     */
    private $section;
    /**
     * @var User
     */
    private $submitterUser;
    /**
     *
     * arbitrary attributes
     * @var Collection|ArticleAttribute[]
     */
    private $attributes;
    /**
     * @var ArrayCollection|ArticleAuthor[]
     * @Groups({"IssueDetail","ArticleDetail"})
     * @Expose
     */
    private $articleAuthors;
    /**
     * @var Collection|ArticleFile[]
     */
    private $articleFiles;
    /**
     * @var Collection|ArticleSubmissionFile[]
     */
    private $articleSubmissionFiles;
    /**
     * @var string
     */
    private $header;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var boolean
     */
    private $setupFinished;

    /** @var  string */
    private $note;

    /**
     * @var ArrayCollection|ArticleStatistic[]
     */
    private $statistics;

    /**
     * @var string
     */
    private $publicURI;

    /**
     * @var integer
     * @GRID\Column(title="numerator")
     */
    private $numerator;

    /**
     * @var int
     * @Grid\Column(field="doiStatus", title="doi.status", filter="select", selectFrom="values", values={
     *     "-3"="status.doi.not_requested",
     *     "-2"="status.doi.not_available",
     *     "0"="status.doi.requested",
     *     "1"="status.doi.valid"
     * })
     */
    protected $doiStatus = DoiStatuses::NOT_AVAILABLE;

    /**
     * List of Article Status
     * @var array
     */
    public static $statuses = array(
        ArticleStatuses::STATUS_UNPUBLISHED => 'status.unpublished',
        ArticleStatuses::STATUS_WITHDRAWN => 'status.withdrawn',
        ArticleStatuses::STATUS_REJECTED => 'status.rejected',
        ArticleStatuses::STATUS_NOT_SUBMITTED => 'status.not_submitted',
        ArticleStatuses::STATUS_INREVIEW => 'status.inreview',
        ArticleStatuses::STATUS_PUBLISHED => 'status.published',
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->citations = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->articleAuthors = new ArrayCollection();
        $this->articleFiles = new ArrayCollection();
        $this->articleSubmissionFiles = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->statistics = new ArrayCollection();
        $this->subjects = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
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
     * @param $name
     * @param $value
     * @return $this
     */
    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = new ArticleAttribute($name, $value, $this);

        return $this;
    }

    /**
     * @param $name
     * @return bool|ArticleAttribute
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : false;
    }

    /**
     * @param  Subject $subject
     * @return $this
     */
    public function addSubject(Subject $subject)
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
        }

        return $this;
    }

    /**
     * @param Subject $subject
     */
    public function removeSubjects(Subject $subject)
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\ArticleTranslation
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
        /** @var ArticleTranslation $defaultTranslation */
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new ArticleTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setAbstract($defaultTranslation->getAbstract());
                $translation->setKeywords($defaultTranslation->getKeywords());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * @param  Lang  $language
     * @return $this
     */
    public function addLanguage(Lang $language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * @param  Lang  $language
     * @return $this
     */
    public function removeLanguage(Lang $language)
    {
        $this->languages->removeElement($language);

        return $this;
    }

    /**
     * @return Collection|Lang[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     *
     * @param  Issue $issue
     * @return $this
     */
    public function setIssue(Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * @return ArticleTypes
     */
    public function getArticleType()
    {
        return $this->articleType;
    }

    /**
     *
     * @param  ArticleTypes $articleType
     * @return $this
     */
    public function setArticleType(ArticleTypes $articleType)
    {
        $this->articleType = $articleType;

        return $this;
    }

    /**
     * @return ArrayCollection|ArticleAuthor[]
     */
    public function getArticleAuthors()
    {
        return $this->articleAuthors;
    }

    /**
     * @return Collection|ArticleFile[]
     */
    public function getArticleFiles()
    {
        return $this->articleFiles;
    }

    /**
     * @return Collection|ArticleSubmissionFile[]
     */
    public function getArticleSubmissionFiles()
    {
        return $this->articleSubmissionFiles;
    }

    /**
     * Add citation
     *
     * @param  Citation $citation
     * @return $this
     */
    public function addCitation(Citation $citation)
    {
        if(!$this->citations->contains($citation)){
            $this->citations->add($citation);
            $citation->addArticle($this);
        }

        return $this;
    }

    /**
     * Remove citation
     *
     * @param  Citation $citation
     * @return $this
     */
    public function removeCitation(Citation $citation)
    {
        if($this->citations->contains($citation)){
            $this->citations->removeElement($citation);
            $citation->removeArticle($this);
        }

        return $this;
    }

    /**
     * Get citations
     *
     * @return Collection|Citation[]
     */
    public function getCitations()
    {
        return $this->citations;
    }

    /**
     *
     * @return string
     */
    public function getStatusText()
    {
        if (!isset(Article::$statuses[$this->status])) {
            return null;
        }

        return Article::$statuses[$this->status];
    }

    /**
     * @param $statusText
     * @return Article
     */
    public function setStatusText($statusText)
    {
        $flippedStatuses = array_flip(Article::$statuses);
        $this->status = null;
        if (isset($flippedStatuses[$statusText])) {
            $this->status = $flippedStatuses[$statusText];
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param  int   $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getOrderNum()
    {
        return $this->orderNum;
    }

    /**
     *
     * @param  integer $orderNum
     * @return $this
     */
    public function setOrderNum($orderNum)
    {
        $this->orderNum = $orderNum;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPrimaryLanguage()
    {
        return $this->primaryLanguage;
    }

    /**
     *
     * @param  string $primaryLanguage
     * @return $this
     */
    public function setPrimaryLanguage($primaryLanguage)
    {
        $this->primaryLanguage = $primaryLanguage;

        return $this;
    }

    /**
     * @return User
     */
    public function getSubmitterUser()
    {
        return $this->submitterUser;
    }

    /**
     * @param User $submitterUser
     * @return $this
     */
    public function setSubmitterUser(User $submitterUser)
    {
        $this->submitterUser = $submitterUser;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->getLogicalFieldTranslation('keywords', false);
    }

    /**
     * @param $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->translate()->setKeywords($keywords);

        return $this;
    }

    /**
     * Get doi
     *
     * @return string
     */
    public function getDoi()
    {
        return $this->doi;
    }

    /**
     * Set doi
     *
     * @param  string $doi
     * @return $this
     */
    public function setDoi($doi)
    {
        $this->doi = $doi;

        return $this;
    }

    /**
     * Get otherId
     *
     * @return string
     */
    public function getOtherId()
    {
        return $this->otherId;
    }

    /**
     * Set otherId
     *
     * @param  string $otherId
     * @return $this
     */
    public function setOtherId($otherId)
    {
        $this->otherId = $otherId;

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
     * @param  Journal $journal
     * @return $this
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;
        $journal->addArticle($this);

        return $this;
    }

    /**
     * Get section
     *
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set section
     * @param  Section $section
     * @return $this
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get setupFinished
     *
     * @return boolean
     */
    public function isSetupFinished()
    {
        return $this->setupFinished;
    }

    /**
     * Set setupFinished
     *
     * @param  string $setupFinished
     * @return $this
     */
    public function setSetupFinished($setupFinished)
    {
        $this->setupFinished = $setupFinished;

        return $this;
    }

    /**
     * Get titleTransliterated
     *
     * @return string
     */
    public function getTitleTransliterated()
    {
        return $this->titleTransliterated;
    }

    /**
     * Set titleTransliterated
     *
     * @param  string $titleTransliterated
     * @return $this
     */
    public function setTitleTransliterated($titleTransliterated)
    {
        $this->titleTransliterated = $titleTransliterated;

        return $this;
    }

    /**
     * Get anonymous
     *
     * @return boolean
     */
    public function isAnonymous()
    {
        return $this->anonymous;
    }

    /**
     * Set anonymous
     *
     * @param  boolean $anonymous
     * @return Article
     */
    public function setAnonymous($anonymous)
    {
        $this->anonymous = $anonymous;

        return $this;
    }

    /**
     * Get pubdate
     *
     * @return \DateTime
     */
    public function getPubdate()
    {
        return $this->pubdate;
    }

    /**
     * Set pubdate
     *
     * @param  \DateTime $pubdate
     * @return $this
     */
    public function setPubdate($pubdate)
    {
        $this->pubdate = $pubdate;

        return $this;
    }

    /**
     * Get submissionDate
     *
     * @return \DateTime
     */
    public function getSubmissionDate()
    {
        return $this->submissionDate;
    }

    /**
     * Set submissionDate
     *
     * @param  \DateTime $submissionDate
     * @return $this
     */
    public function setSubmissionDate($submissionDate)
    {
        $this->submissionDate = $submissionDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAcceptanceDate()
    {
        return $this->acceptanceDate;
    }

    /**
     * @param \DateTime $acceptanceDate
     * @return Article
     */
    public function setAcceptanceDate($acceptanceDate)
    {
        $this->acceptanceDate = $acceptanceDate;

        return $this;
    }

    /**
     * Get pubdateSeason
     *
     * @return string
     */
    public function getPubdateSeason()
    {
        return $this->pubdateSeason;
    }

    /**
     * Set pubDateSeason
     *
     * @param  string $pubDateSeason
     * @return $this
     */
    public function setPubdateSeason($pubDateSeason)
    {
        $this->pubdateSeason = $pubDateSeason;

        return $this;
    }

    /**
     * Get firstPage
     *
     * @return integer
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }

    /**
     * Set firstPage
     *
     * @param  integer $firstPage
     * @return $this
     */
    public function setFirstPage($firstPage)
    {
        $this->firstPage = $firstPage;

        return $this;
    }

    /**
     * Get lastPage
     *
     * @return integer
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * Set lastPage
     *
     * @param  integer $lastPage
     * @return $this
     */
    public function setLastPage($lastPage)
    {
        $this->lastPage = $lastPage;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set uri
     *
     * @param  string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
        return $this->getLogicalFieldTranslation('abstract', false);
    }

    /**
     * Set abstract
     *
     * @param  string $abstract
     * @return $this
     */
    public function setAbstract($abstract)
    {
        $this->translate()->setAbstract($abstract);

        return $this;
    }

    /**
     * Get abstractTransliterated
     *
     * @return string
     */
    public function getAbstractTransliterated()
    {
        return $this->abstractTransliterated;
    }

    /**
     * Set abstractTransliterated
     *
     * @param  string $abstractTransliterated
     * @return $this
     */
    public function setAbstractTransliterated($abstractTransliterated)
    {
        $this->abstractTransliterated = $abstractTransliterated;

        return $this;
    }

    /**
     * Remove attributes
     *
     * @param ArticleAttribute $attributes
     */
    public function removeAttribute(ArticleAttribute $attributes)
    {
        $this->attributes->removeElement($attributes);
    }

    /**
     * Get attributes
     *
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Add articleAuthor
     *
     * @param  ArticleAuthor $articleAuthor
     * @return $this
     */
    public function addArticleAuthor(ArticleAuthor $articleAuthor)
    {
        if(!$this->articleAuthors->contains($articleAuthor)){
            $this->articleAuthors->add($articleAuthor);
            $articleAuthor->setArticle($this);
        }

        return $this;
    }

    /**
     * Remove articleAuthor
     *
     * @param ArticleAuthor $articleAuthor
     * @return $this
     */
    public function removeArticleAuthor(ArticleAuthor $articleAuthor)
    {
        if($this->articleAuthors->contains($articleAuthor)){
            $this->articleAuthors->removeElement($articleAuthor);
        }
        return $this;
    }

    /**
     * Add articleFiles
     *
     * @param  ArticleFile $articleFile
     * @return $this
     */
    public function addArticleFile(ArticleFile $articleFile)
    {
        if(!$this->articleFiles->contains($articleFile)){
            $this->articleFiles->add($articleFile);
            $articleFile->setArticle($this);
        }

        return $this;
    }

    /**
     * Remove articleFiles
     *
     * @param ArticleFile $articleFile
     */
    public function removeArticleFile(ArticleFile $articleFile)
    {
        if($this->articleFiles->contains($articleFile)){
            $this->articleFiles->removeElement($articleFile);
        }
    }

    /**
     * Add articleSubmissionFiles
     *
     * @param  ArticleSubmissionFile $articleSubmissionFile
     * @return $this
     */
    public function addArticleSubmissionFile(ArticleSubmissionFile $articleSubmissionFile)
    {
        if(!$this->articleSubmissionFiles->contains($articleSubmissionFile)){
            $this->articleSubmissionFiles->add($articleSubmissionFile);
            $articleSubmissionFile->setArticle($this);
        }

        return $this;
    }

    /**
     * Remove articleSubmissionFiles
     *
     * @param ArticleSubmissionFile $articleSubmissionFile
     */
    public function removeArticleSubmissionFile(ArticleSubmissionFile $articleSubmissionFile)
    {
        if($this->articleSubmissionFiles->contains($articleSubmissionFile)){
            $this->articleSubmissionFiles->removeElement($articleSubmissionFile);
        }
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param  mixed $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle()."[#{$this->getId()}]";
    }

    /**
     * Get Title
     * @param bool $withLocale
     *
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
        /** @var ArticleTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return $this
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Article
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
     * @return Article
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return ArrayCollection|\Ojs\AnalyticsBundle\Entity\ArticleStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|ArticleStatistic[] $statistics
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
     */
    public function setPublicURI($publicURI)
    {
        $this->publicURI = $publicURI;
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
     */
    public function setNumerator($numerator)
    {
        $this->numerator = $numerator;
    }

    /**
     * @return bool return if article is indexable
     */
    public function isIndexable()
    {
        if($this->getStatus() == ArticleStatuses::STATUS_PUBLISHED){
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getDoiStatus()
    {
        return $this->doiStatus;
    }

    /**
     * @param int $doiStatus
     * @return Article
     */
    public function setDoiStatus($doiStatus)
    {
        $this->doiStatus = $doiStatus;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDoiRequestTime()
    {
        return $this->doiRequestTime;
    }

    /**
     * @param \DateTime $doiRequestTime
     * @return $this
     */
    public function setDoiRequestTime($doiRequestTime)
    {
        $this->doiRequestTime = $doiRequestTime;

        return $this;
    }
}
