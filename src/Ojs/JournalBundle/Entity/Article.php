<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\Common\Params\ArticleParams;
use Ojs\UserBundle\Entity\UserArticleRole;

/**
 * Article
 * @GRID\Source(columns="id ,title, issue.title, doi, journal.title, pubdate, section.title")
 * @GRID\Source(columns="id, status, title, journal.title", groups={"submission"})
 * @ExclusionPolicy("all")
 */
class Article implements Translatable
{
    use GenericEntityTrait;
    /**
     * auto-incremented article unique id
     * @GRID\Column(title="id")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $id;

    /**
     *
     * @var integer
     * @Expose
     */
    private $issueId;

    /**
     * @var integer
     * @Expose
     */
    private $articleTypeId;

    /**
     * @var integer
     * @Expose
     * @GRID\Column(type="text", groups={"submission"})
     */
    private $status;

    /**
     * user id of the owner of this article
     * @var integer
     * @Expose
     */
    private $submitterId;

    /**
     * (optional)
     * @var string
     * @GRID\Column(title="OAI")
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     */
    private $otherId;

    /**
     * @var integer
     * @Expose
     */
    private $journalId;

    /**
     * Original article title
     * @var string
     * @GRID\Column(title="title")
     * @Expose
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
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $subtitle;

    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $keywords;

    /**
     * Some articles carries no authorship
     * @var boolean
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $isAnonymous;

    /**
     * @var \DateTime
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $submissionDate;

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
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail","ArticleDetail"})
     */
    private $part;

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
     * @JMS\Expose
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
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $abstract;

    /**
     * @var string
     * @Expose
     */
    private $subjects;

    /**
     * @var Collection|Lang[]
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $languages;

    /**
     * @var Issue
     * @GRID\Column(field="issue.title", title="issue")
     */
    private $issue;

    /**
     * @var ArticleTypes
     */
    private $articleType;

    /**
     * @var Collection|Citation[]
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $citations;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * @var JournalSection
     */
    private $section;

    /**
     *
     * @var int
     * @Expose
     */
    private $sectionId;

    /**
     *
     * arbitrary attributes
     * @var Collection|ArticleAttribute[]
     */
    private $attributes;

    /**
     * @var Collection|ArticleAuthor[]
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $articleAuthors;

    /**
     * @var Collection|ArticleFile[]
     * @Expose
     */
    private $articleFiles;

    /**
     * @var string
     */
    private $header;

    /**
     * (optional) English transliterated abstract
     * @var string
     * @Expose
     */
    protected $abstractTransliterated;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    protected $header_options;

    /** @var ArrayCollection|UserArticleRole[] */
    private $userRoles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->citations = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->articleAuthors = new ArrayCollection();
        $this->articleFiles = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
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
     * Get subjects
     *
     * @return string
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Set subjects
     *
     * @param  string $subjects
     * @return $this
     */
    public function setSubjects($subjects = null)
    {
        $this->subjects = $subjects;

        return $this;
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
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
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
     * @return ArticleTypes
     */
    public function getArticleType()
    {
        return $this->articleType;
    }

    /**
     * @return Collection|ArticleAuthor[]
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
     * Add citation
     *
     * @param  Citation $citation
     * @return $this
     */
    public function addCitation(Citation $citation)
    {
        $this->citations->add($citation);

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
        $this->citations->removeElement($citation);

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
        return ArticleParams::statusText($this->status);
    }

    /**
     *
     * @return string
     */
    public function getStatusColor()
    {
        return ArticleParams::statusColor($this->status);
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
     * @return mixed
     */
    public function getSubmitterId()
    {
        return $this->submitterId;
    }

    /**
     * @param $submitterId
     * @return $this
     */
    public function setSubmitterId($submitterId)
    {
        $this->submitterId = $submitterId;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
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
     * Get doi
     *
     * @return string
     */
    public function getDoi()
    {
        return $this->doi;
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
     * Get otherId
     *
     * @return string
     */
    public function getOtherId()
    {
        return $this->otherId;
    }

    /**
     * Set issueId
     *
     * @param  integer $issueId
     * @return $this
     */
    public function setIssueId($issueId)
    {
        $this->issueId = $issueId;

        return $this;
    }

    /**
     * Get issueId
     * @return integer
     */
    public function getIssueId()
    {
        return $this->issueId;
    }

    /**
     * Set articleTypeId
     *
     * @param  integer $articleTypeId
     * @return $this
     */
    public function setArticleTypeId($articleTypeId)
    {
        $this->articleTypeId = $articleTypeId;

        return $this;
    }

    /**
     * Get articleTypeId
     * @return integer
     */
    public function getArticleTypeId()
    {
        return $this->articleTypeId;
    }

    /**
     * Set journalId
     *
     * @param  integer $journalId
     * @return $this
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
     * @param  Journal $journal
     * @return $this
     */
    public function setJournal(Journal $journal)
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
     * Set sectionId
     *
     * @param  integer $sectionId
     * @return $this
     */
    public function setSectionId($sectionId)
    {
        $this->sectionId = $sectionId;

        return $this;
    }

    /**
     * Get sectionId
     *
     * @return integer
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * Set section
     * @param  JournalSection $section
     * @return $this
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return JournalSection
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return $this
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
     * Get titleTransliterated
     *
     * @return string
     */
    public function getTitleTransliterated()
    {
        return $this->titleTransliterated;
    }

    /**
     * Set subtitle
     *
     * @param  string $subtitle
     * @return $this
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set isAnonymous
     *
     * @param  boolean $isAnonymous
     * @return $this
     */
    public function setIsAnonymous($isAnonymous)
    {
        $this->isAnonymous = $isAnonymous;

        return $this;
    }

    /**
     * Get isAnonymous
     *
     * @return boolean
     */
    public function getIsAnonymous()
    {
        return $this->isAnonymous;
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
     * Get pubdate
     *
     * @return \DateTime
     */
    public function getPubdate()
    {
        return $this->pubdate;
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
     * Get submissionDate
     *
     * @return \DateTime
     */
    public function getSubmissionDate()
    {
        return $this->submissionDate;
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
     * Get pubdateSeason
     *
     * @return string
     */
    public function getPubdateSeason()
    {
        return $this->pubdateSeason;
    }

    /**
     * Set part
     *
     * @param  string $part
     * @return $this
     */
    public function setPart($part)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return string
     */
    public function getPart()
    {
        return $this->part;
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
     * Get firstPage
     *
     * @return integer
     */
    public function getFirstPage()
    {
        return $this->firstPage;
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
     * Get lastPage
     *
     * @return integer
     */
    public function getLastPage()
    {
        return $this->lastPage;
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
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set abstract
     *
     * @param  string $abstract
     * @return $this
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
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
     * Get abstractTransliterated
     *
     * @return string
     */
    public function getAbstractTransliterated()
    {
        return $this->abstractTransliterated;
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
     * Add articleAuthors
     *
     * @param  ArticleAuthor $articleAuthors
     * @return $this
     */
    public function addArticleAuthor(ArticleAuthor $articleAuthors)
    {
        $this->articleAuthors[] = $articleAuthors;

        return $this;
    }

    /**
     * Remove articleAuthors
     *
     * @param ArticleAuthor $articleAuthors
     */
    public function removeArticleAuthor(ArticleAuthor $articleAuthors)
    {
        $this->articleAuthors->removeElement($articleAuthors);
    }

    /**
     * Add articleFiles
     *
     * @param  ArticleFile $articleFiles
     * @return $this
     */
    public function addArticleFile(ArticleFile $articleFiles)
    {
        $this->articleFiles[] = $articleFiles;

        return $this;
    }

    /**
     * Remove articleFiles
     *
     * @param ArticleFile $articleFiles
     */
    public function removeArticleFile(ArticleFile $articleFiles)
    {
        $this->articleFiles->removeElement($articleFiles);
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
     * @return ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param  UserArticleRole $role
     * @return $this
     */
    public function addUserRole(UserArticleRole $role)
    {
        $this->userRoles->add($role);

        return $this;
    }

    /**
     * @param  UserArticleRole $role
     * @return $this
     */
    public function removeUserRole(UserArticleRole $role)
    {
        $this->userRoles->removeElement($role);

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderOptions()
    {
        return $this->header_options;
    }

    /**
     * @param  string $header_options
     * @return $this
     */
    public function setHeaderOptions($header_options)
    {
        $this->header_options = $header_options;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle()."[#{$this->getId()}]";
    }
}
