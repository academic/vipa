<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation as JMS;
use Ojs\Common\Entity\GenericEntityTrait;
use Ojs\Common\Params\ArticleParams;
use Ojs\UserBundle\Entity\UserArticleRole;
use GoDisco\AclTreeBundle\Annotation\AclParent;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Article
 * @JMS\ExclusionPolicy("all")
 * @GRID\Source(columns="id ,title, issue.title, doi, journal.title, pubdate, section.title")
 * @GRID\Source(columns="id ,status,title, journal.title",groups={"submission"})
 */
class Article implements Translatable
{
    use GenericEntityTrait;
    /**
     * auto-incremented article unique id
     * @var integer
     * @JMS\Expose
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     *
     * @var integer
     * @JMS\Expose
     */
    private $issueId;

    /**
     * @var integer
     * @JMS\Expose
     * @GRID\Column(type="text", groups={"submission"})
     */
    private $status;

    /**
     * user id of the owner of this article
     * @var integer
     * @JMS\Expose
     */
    private $submitterId;

    /**
     * (optional)
     * @var string
     * @JMS\Expose
     * @GRID\Column(title="OAI")
     */
    private $doi;

    /**
     * Could contain any article ID used by the provider
     * @var string
     * @JMS\Expose
     */
    private $otherId;

    /**
     * @var integer
     * @JMS\Expose
     */
    private $journalId;

    /**
     * Original article title
     * @var string
     * @JMS\Expose
     * @GRID\Column(title="title")
     */
    private $title;

    /**
     * Roman transliterated title
     * @var string
     * @JMS\Expose
     */
    private $titleTransliterated;

    /**
     * @var string
     * @JMS\Expose
     */
    private $subtitle;

    /**
     * @var string
     * @JMS\Expose
     */
    private $keywords;

    /**
     * Some articles carries no authorship
     * @var boolean
     * @JMS\Expose
     */
    private $isAnonymous;

    /**
     * @var \DateTime
     * @JMS\Expose
     */
    private $submissionDate;

    /**
     * @var \DateTime
     * @JMS\Expose
     * @GRID\Column(title="pubdate")
     */
    private $pubdate;

    /**
     * @var string
     * @JMS\Expose
     */
    private $pubdateSeason;

    /**
     * @var string
     * @JMS\Expose
     */
    private $part;

    /**
     * @var integer
     * @JMS\Expose
     */
    private $firstPage;

    /**
     * @var integer
     * @JMS\Expose
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
     * @JMS\Expose
     */
    private $primaryLanguage;

    /**
     *
     * @var integer
     * @JMS\Expose
     */
    private $orderNum;

    /**
     * Original abstract
     * @var string
     * @JMS\Expose
     */
    private $abstract;

    /**
     * @var string
     * @JMS\Expose
     */
    private $subjects;

    /**
     * @var Collection
     * @JMS\Expose
     */
    private $languages;

    /**
     * @var Issue
     * @GRID\Column(field="issue.title", title="issue")
     * AclParent
     */
    private $issue;

    /**
     * @var Collection
     *
     */
    private $citations;

    /**
     * @var Journal
     * @JMS\Expose
     * @GRID\Column(field="journal.title", title="journal")
     * @AclParent
     */
    private $journal;

    /**
     * @var JournalSection
     * @JMS\Expose
     */
    private $section;

    /**
     *
     * @var int
     */
    private $sectionId;

    /**
     *
     * arbitrary attributes
     * @var Collection|ArticleAttribute[]
     */
    private $attributes;

    /**
     * @var Collection
     * @JMS\Expose
     */
    private $articleAuthors;

    /**
     * @var Collection
     */
    private $articleFiles;

    /**
     * @var string
     */
    private $header;

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

    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = new ArticleAttribute($name, $value, $this);
    }

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
     * @param  string  $subjects
     * @return Article
     */
    public function setSubjects($subjects = null)
    {
        $this->subjects = $subjects;

        return $this;
    }

    /**
     * @param  Lang    $language
     * @return Article
     */
    public function addLanguage(Lang $language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * @param Lang $language
     */
    public function removeLanguage(Lang $language)
    {
        $this->languages->removeElement($language);
    }

    /**
     * @return Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     *
     * @param  Issue   $issue
     * @return Article
     */
    public function setIssue(Issue $issue)
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
     * @return Collection
     */
    public function getArticleAuthors()
    {
        return $this->articleAuthors;
    }

    /**
     * @return Collection
     */
    public function getArticlefiles()
    {
        return $this->articleFiles;
    }

    /**
     * Add citation
     *
     * @param  Citation $citation
     * @return Article
     */
    public function addCitation(Citation $citation)
    {
        $this->citations[] = $citation;

        return $this;
    }

    /**
     * Remove citation
     *
     * @param Citation $citation
     */
    public function removeCitation(Citation $citation)
    {
        $this->citations->removeElement($citation);
    }

    /**
     * Get citations
     *
     * @return Collection
     */
    public function getCitations()
    {
        return $this->citations;
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

    public function getStatus()
    {
        return $this->status;
    }

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
     * @return Article
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
     * @param  string  $primaryLanguage
     * @return Article
     */
    public function setPrimaryLanguage($primaryLanguage)
    {
        $this->primaryLanguage = $primaryLanguage;

        return $this;
    }

    public function getSubmitterId()
    {
        return $this->submitterId;
    }

    public function setSubmitterId($submitterId)
    {
        $this->submitterId = $submitterId;

        return $submitterId;
    }

    /**
     * (optional) English transliterated abstract
     * @var string
     * @JMS\Expose
     */
    protected $abstractTransliterated;

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $keywords;
    }

    /**
     * Set doi
     *
     * @param  string  $doi
     * @return Article
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
     * @param  string  $otherId
     * @return Article
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
     * @return Article
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
     * Set journalId
     *
     * @param  integer $journalId
     * @return Article
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
     * @return Article
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
     * Set sectionId
     *
     * @param  integer $sectionId
     * @return Article
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
     * @return Article
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
     * @param  string  $title
     * @return Article
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
     * @param  string  $titleTransliterated
     * @return Article
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
     * @param  string  $subtitle
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @param  string  $pubDateSeason
     * @return Article
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
     * @param  string  $part
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @param  string  $uri
     * @return Article
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
     * @param  string  $abstract
     * @return Article
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
     * @param  string  $abstractTransliterated
     * @return Article
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
     * @return Article
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
     * @return Article
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

    private $slug;

    /**
     * @return mixed
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

    public function __toString()
    {
        return $this->getTitle()."[#{$this->getId()}]";
    }

    /** @var ArrayCollection */
    private $userRoles;

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
     * @var string
     */
    protected $header_options;

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
}
