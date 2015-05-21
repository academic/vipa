<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 8.02.15
 * Time: 14:24
 */
namespace Ojs\JournalBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * This collection holds journal application data
 * @MongoDB\Document(collection="journal_application")
 * @GRID\Source(columns="id,title,subtitle,status")
 */
class JournalApplication
{
    /**
     * @MongoDB\Id
     * @var integer
     * @GRID\Column(title="journal.id")
     */
    protected $id;
    /**
     * @MongoDB\String
     * @var string
     * @GRID\Column(title="journal.title")
     */
    protected $title;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $titleAbbr;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $titleTransliterated;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $path;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $domain;
    /**
     * @MongoDB\String
     * @var string
     * @GRID\Column(title="journal.subtitle")
     */
    protected $subtitle;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $issn;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $eissn;
    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $firstPublishDate;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $period;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $url;
    /**
     * @MongoDB\Int
     * @var int
     */
    protected $country;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $cover_image;
    /**
     * @MongoDB\String
     * @var string
     */
    protected $header_image;
    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $createdAt;
    /**
     * @MongoDB\Int
     * @var integer
     */
    protected $user;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $tags;

    /**
     * @MongoDB\Collection
     * @var ArrayCollection
     */
    protected $languages;

    /**
     * @MongoDB\Collection
     * @var ArrayCollection
     */
    protected $subjects;
    /**
     * @MongoDB\Int
     * @var integer
     */
    protected $institution;

    /**
     * @MongoDB\Boolean
     * @var bool
     */
    protected $merged;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $address;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $phone;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $email;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $editorName;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $editorSurname;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $editorPhone;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $editorEmail;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $assistantEditorName;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $assistantEditorSurname;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $assistantEditorPhone;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $assistantEditorEmail;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $techContactName;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $techContactSurname;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $techContactPhone;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $techContactEmail;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $status;

    /**
     * @return int
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param int $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCoverImage()
    {
        return $this->cover_image;
    }

    /**
     * @param string $cover_image
     */
    public function setCoverImage($cover_image)
    {
        $this->cover_image = $cover_image;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getEissn()
    {
        return $this->eissn;
    }

    /**
     * @param string $eissn
     */
    public function setEissn($eissn)
    {
        $this->eissn = $eissn;
    }

    /**
     * @return \DateTime
     */
    public function getFirstPublishDate()
    {
        return $this->firstPublishDate;
    }

    /**
     * @param \DateTime $firstPublishDate
     */
    public function setFirstPublishDate($firstPublishDate)
    {
        $this->firstPublishDate = $firstPublishDate;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->header_image;
    }

    /**
     * @param string $header_image
     */
    public function setHeaderImage($header_image)
    {
        $this->header_image = $header_image;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return string
     */
    public function getIssn()
    {
        return $this->issn;
    }

    /**
     * @param string $issn
     */
    public function setIssn($issn)
    {
        $this->issn = $issn;
    }

    /**
     * @return ArrayCollection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param ArrayCollection $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param string $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param ArrayCollection $subjects
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitleAbbr()
    {
        return $this->titleAbbr;
    }

    /**
     * @param string $titleAbbr
     */
    public function setTitleAbbr($titleAbbr)
    {
        $this->titleAbbr = $titleAbbr;
    }

    /**
     * @return string
     */
    public function getTitleTransliterated()
    {
        return $this->titleTransliterated;
    }

    /**
     * @param string $titleTransliterated
     */
    public function setTitleTransliterated($titleTransliterated)
    {
        $this->titleTransliterated = $titleTransliterated;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function isMerged()
    {
        return $this->merged;
    }

    /**
     * @param boolean $merged
     */
    public function setMerged($merged)
    {
        $this->merged = $merged;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEditorName()
    {
        return $this->editorName;
    }

    /**
     * @param string $editorName
     */
    public function setEditorName($editorName)
    {
        $this->editorName = $editorName;
    }

    /**
     * @return string
     */
    public function getEditorSurname()
    {
        return $this->editorSurname;
    }

    /**
     * @param string $editorSurname
     */
    public function setEditorSurname($editorSurname)
    {
        $this->editorSurname = $editorSurname;
    }

    /**
     * @return string
     */
    public function getEditorPhone()
    {
        return $this->editorPhone;
    }

    /**
     * @param string $editorPhone
     */
    public function setEditorPhone($editorPhone)
    {
        $this->editorPhone = $editorPhone;
    }

    /**
     * @return string
     */
    public function getEditorEmail()
    {
        return $this->editorEmail;
    }

    /**
     * @param string $editorEmail
     */
    public function setEditorEmail($editorEmail)
    {
        $this->editorEmail = $editorEmail;
    }

    /**
     * @return string
     */
    public function getAssistantEditorName()
    {
        return $this->assistantEditorName;
    }

    /**
     * @param string $assistantEditorName
     */
    public function setAssistantEditorName($assistantEditorName)
    {
        $this->assistantEditorName = $assistantEditorName;
    }

    /**
     * @return string
     */
    public function getAssistantEditorSurname()
    {
        return $this->assistantEditorSurname;
    }

    /**
     * @param string $assistantEditorSurname
     */
    public function setAssistantEditorSurname($assistantEditorSurname)
    {
        $this->assistantEditorSurname = $assistantEditorSurname;
    }

    /**
     * @return string
     */
    public function getAssistantEditorPhone()
    {
        return $this->assistantEditorPhone;
    }

    /**
     * @param string $assistantEditorPhone
     */
    public function setAssistantEditorPhone($assistantEditorPhone)
    {
        $this->assistantEditorPhone = $assistantEditorPhone;
    }

    /**
     * @return string
     */
    public function getAssistantEditorEmail()
    {
        return $this->assistantEditorEmail;
    }

    /**
     * @param string $assistantEditorEmail
     */
    public function setAssistantEditorEmail($assistantEditorEmail)
    {
        $this->assistantEditorEmail = $assistantEditorEmail;
    }

    /**
     * @return string
     */
    public function getTechContactName()
    {
        return $this->techContactName;
    }

    /**
     * @param string $techContactName
     */
    public function setTechContactName($techContactName)
    {
        $this->techContactName = $techContactName;
    }

    /**
     * @return string
     */
    public function getTechContactSurname()
    {
        return $this->techContactSurname;
    }

    /**
     * @param string $techContactSurname
     */
    public function setTechContactSurname($techContactSurname)
    {
        $this->techContactSurname = $techContactSurname;
    }


    /**
     * @return string
     */
    public function getTechContactPhone()
    {
        return $this->techContactPhone;
    }

    /**
     * @param string $techContactPhone
     */
    public function setTechContactPhone($techContactPhone)
    {
        $this->techContactPhone = $techContactPhone;
    }

    /**
     * @return string
     */
    public function getTechContactEmail()
    {
        return $this->techContactEmail;
    }

    /**
     * @param string $techContactEmail
     */
    public function setTechContactEmail($techContactEmail)
    {
        $this->techContactEmail = $techContactEmail;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
