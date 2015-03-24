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
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\UserBundle\Entity\User;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * This collection holds journal suggestion data
 * @MongoDB\Document(collection="journal_suggestion")
 * @GRID\Source(columns="id,title,subtitle")
 */
class JournalSuggestion
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

}