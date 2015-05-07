<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\UserBundle\Entity\UserJournalRole;
use Okulbilisim\LocationBundle\Entity\Location;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Journal
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,title,issn,eissn,country.name,institution")
 */
class Journal extends \Ojs\Common\Entity\GenericExtendedEntity implements Translatable {

    /**
     * @var integer
     * @Expose
     *
     */
    private $id;

    /**
     * @var string
     * @Expose
     */
    private $title;

    /**
     * @var string
     * @Expose
     */
    private $titleAbbr;

    /**
     * @var string
     * @Expose
     */
    private $titleTransliterated;

    /**
     * @var string
     * @Expose
     */
    private $subtitle;

    /**
     * @var string
     * @Expose
     */
    private $path;

    /**
     * @var string
     * @Expose
     */
    private $domain;

    /**
     * @var string
     * @Expose
     */
    private $issn;

    /**
     * @var string
     */
    private $eissn;

    /**
     * @var \DateTime
     * @Expose
     */
    private $firstPublishDate;

    /**
     * @var string
     * @Expose
     */
    private $period;

    /**
     * @var string
     * @Expose
     */
    private $url;

    /**
     * @var Location
     * @Expose
     */
    private $country;

    /**
     * @var integer
     * @Expose
     */
    private $country_id;

    /**
     * @var boolean
     * @Expose
     */
    private $published;

    /**
     * @var integer
     * @Expose
     */
    private $status;

    /**
     * @var string
     * @Expose
     */
    private $image;

    /**
     * @var string
     * @Expose
     */
    private $header;

    /**
     * @var string
     * @Expose
     */
    private $googleAnalyticsId;

    /**
     * @var string
     * @Expose
     */
    private $slug;

    /**
     * @var integer
     * @Expose
     */
    private $themeId;

    /**
     * @var Theme
     * @Expose
     */
    private $theme;

    /**
     * @var boolean
     * @Expose
     */
    private $isConfigured;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $issues;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $boards;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $languages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $subjects;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $submitRoles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sections;

    /**
     *
     * arbitrary settings
     */
    private $settings;

    /**
     * @var \Ojs\JournalBundle\Entity\Institution
     * @Expose
     */
    private $institution;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Expose
     */
    private $journalThemes;

    /**
     * @var integer
     * @Expose
     */
    private $institutionId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bannedUsers;
    private $userRoles;

    /**
     * @var String
     */
    protected $description;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->issues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->boards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->journalThemes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->submitRoles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param  string $settingName
     * @param  string $value
     * @return \Ojs\JournalBundle\Entity\Journal
     */
    public function addSetting($settingName, $value)
    {
        $this->settings[$settingName] = new JournalSetting($settingName, $value, $this);
        return $this;
    }

    /**
     * @param  string $settingName 
     * @return bool
     */
    public function hasSetting($settingName)
    {
        foreach ($this->settings as $setting) {
            if ($setting->getSetting() === $settingName) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param  string $settingName
     * @return JournalSetting|boolean
     */
    public function getSetting($settingName)
    {
        return isset($this->settings[$settingName]) ? $this->settings[$settingName] : false;
    }

    /**
     *
     * @param  string $settingName
     * @return JournalSetting|boolean
     */
    public function getAttribute($settingName)
    {
        return $this->getSetting($settingName);
    }

    /**
     * @param  \Ojs\JournalBundle\Entity\JournalSection $section
     * @return Journal
     */
    public function addSection(\Ojs\JournalBundle\Entity\JournalSection $section)
    {
        $this->sections[] = $section;
        return $this;
    }

    /**
     * @param \Ojs\JournalBundle\Entity\JournalSection $section
     */
    public function removeSection(\Ojs\JournalBundle\Entity\JournalSection $section)
    {
        $this->sections->removeElement($section);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param  \Ojs\JournalBundle\Entity\JournalTheme $journalTheme
     * @return Journal
     */
    public function addJournalThemes(\Ojs\JournalBundle\Entity\JournalTheme $journalTheme)
    {
        $this->journalThemes[] = $journalTheme;
        return $this;
    }

    /**
     * @param \Ojs\JournalBundle\Entity\JournalTheme $journalTheme
     */
    public function removeJournalThemes(\Ojs\JournalBundle\Entity\JournalTheme $journalTheme)
    {
        $this->journalThemes->removeElement($journalTheme);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournalThemes()
    {
        return $this->journalThemes;
    }

    /**
     * @param  \Ojs\JournalBundle\Entity\Lang $language
     * @return Journal
     */
    public function addLanguage(\Ojs\JournalBundle\Entity\Lang $language)
    {
        $this->languages[] = $language;
        return $this;
    }

    /**
     * @param \Ojs\JournalBundle\Entity\Lang $language
     */
    public function removeLanguage(\Ojs\JournalBundle\Entity\Lang $language)
    {
        $this->languages->removeElement($language);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param  \Ojs\UserBundle\Entity\Role $submitRole
     * @return Journal
     */
    public function addSubmitRole(\Ojs\UserBundle\Entity\Role $submitRole)
    {
        $this->submitRoles[] = $submitRole;
        return $this;
    }

    /**
     * @param \Ojs\UserBundle\Entity\Role $submitRole
     */
    public function removeSubmitRole(\Ojs\UserBundle\Entity\Role $submitRole)
    {
        $this->submitRoles->removeElement($submitRole);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubmitRoles()
    {
        return $this->submitRoles;
    }

    /**
     * @param  \Ojs\JournalBundle\Entity\Subject $subject
     * @return Journal
     */
    public function addSubject(\Ojs\JournalBundle\Entity\Subject $subject)
    {
        $this->subjects[] = $subject;
        return $this;
    }

    /**
     * @param \Ojs\JournalBundle\Entity\Subject $subject
     */
    public function removeSubjects(\Ojs\JournalBundle\Entity\Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Journal
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param  string $path
     * @return Journal
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get domain
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set domain
     * @param  string $domain
     * @return Journal
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Get titleAbbr
     *
     * @return string
     */
    public function getTitleAbbr()
    {
        return $this->titleAbbr;
    }

    /**
     * Set titleAbbr
     *
     * @param  string $titleAbbr
     * @return Journal
     */
    public function setTitleAbbr($titleAbbr)
    {
        $this->titleAbbr = $titleAbbr;

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
     * @return Journal
     */
    public function setTitleTransliterated($titleTransliterated)
    {
        $this->titleTransliterated = $titleTransliterated;
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
     * Set subtitle
     *
     * @param  string $subtitle
     * @return Journal
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get issn
     *
     * @return string
     */
    public function getIssn()
    {
        return $this->issn;
    }

    /**
     * Set issn
     *
     * @param  string $issn
     * @return Journal
     */
    public function setIssn($issn)
    {
        $this->issn = $issn;

        return $this;
    }

    /**
     * Get eissn
     *
     * @return string
     */
    public function getEissn()
    {
        return $this->eissn;
    }

    /**
     * Set eissn
     *
     * @param  string $eissn
     * @return Journal
     */
    public function setEissn($eissn)
    {
        $this->eissn = $eissn;

        return $this;
    }

    /**
     * Get firstPublishDate
     *
     * @return \DateTime
     */
    public function getFirstPublishDate()
    {
        return $this->firstPublishDate;
    }

    /**
     * Set firstPublishDate
     *
     * @param  \DateTime $firstPublishDate
     * @return Journal
     */
    public function setFirstPublishDate($firstPublishDate)
    {
        $this->firstPublishDate = $firstPublishDate;

        return $this;
    }

    /**
     * Get period
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set period
     *
     * @param  string $period
     * @return Journal
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param  string $url
     * @return Journal
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get institutionId
     * @return integer
     */
    public function getInstitutionId()
    {
        return $this->institutionId;
    }

    /**
     * Set institutionId
     * @param  int $institutionId
     * @return Journal
     */
    public function setInstitutionId($institutionId)
    {
        $this->institutionId = $institutionId;
        return $this;
    }

    /**
     * @return \Ojs\JournalBundle\Entity\Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set institution
     * @param  \Ojs\JournalBundle\Entity\Institution $institution
     * @return Journal
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * Get country
     *
     * @return Location
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     * @param  Location $country
     * @return Journal
     */
    public function setCountry(Location $country)
    {
        $this->country = $country;
        $this->country_id = $country->getId();
        return $this;
    }

    /**
     * @return int
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * @param int $country_id
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set published
     *
     * @param  boolean $published
     * @return Journal
     */
    public function setPublished($published = false)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published ? true : false;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param  integer $status
     * @return Journal
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param  string $image
     * @return Journal
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set slug
     *
     * @param  string $slug
     * @return Journal
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get themeId
     *
     * @return integer
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    /**
     * Set themeId
     *
     * @param  integer $themeId
     * @return Journal
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;
        return $this;
    }

    /**
     * Get theme
     *
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme
     *
     * @param  Theme $theme
     * @return Journal
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Get googleAnalyticsId
     *
     * @return Theme
     */
    public function getGoogleAnalyticsId()
    {
        return $this->googleAnalyticsId;
    }

    /**
     * Set googleAnalyticsId
     *
     * @param  string $googleAnalyticsId
     * @return Journal
     */
    public function setGoogleAnalyticsId($googleAnalyticsId)
    {
        $this->googleAnalyticsId = $googleAnalyticsId;
        return $this;
    }

    /**
     * Get isConfigured
     * @return boolean
     */
    public function getIsConfigured()
    {
        return $this->isConfigured;
    }

    /**
     * Set themeId
     *
     * @param  boolean $isConfigured
     * @return Journal
     */
    public function setIsConfigured($isConfigured)
    {
        $this->isConfigured = $isConfigured;
        return $this;
    }

    /**
     * Add articles
     *
     * @param  \Ojs\JournalBundle\Entity\Article $article
     * @return Journal
     */
    public function addArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Ojs\JournalBundle\Entity\Article $article
     */
    public function removeArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add board
     *
     * @param  \Ojs\JournalBundle\Entity\Board $boards
     * @return Journal
     */
    public function addBoard(\Ojs\JournalBundle\Entity\Board $board)
    {
        $this->boards[] = $board;

        return $this;
    }

    /**
     * Remove board
     *
     * @param \Ojs\JournalBundle\Entity\Board $board
     */
    public function removeBoard(\Ojs\JournalBundle\Entity\Board $board)
    {
        $this->boards->removeElement($board);
    }

    /**
     * Get boards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add issue
     *
     * @param  \Ojs\JournalBundle\Entity\Issue $issues
     * @return Journal
     */
    public function addIssue(\Ojs\JournalBundle\Entity\Issue $issue)
    {
        $this->issues[] = $issue;

        return $this;
    }

    /**
     * Remove issue
     *
     * @param \Ojs\JournalBundle\Entity\Issue $issue
     */
    public function removeIssue(\Ojs\JournalBundle\Entity\Issue $issue)
    {
        $this->issues->removeElement($issue);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Remove articles
     *
     * @param \Ojs\UserBundle\Entity\User $users
     */
    public function removeUser(\Ojs\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add articles
     *
     * @param  \Ojs\UserBundle\Entity\User $users
     * @return Journal
     */
    public function addUser(\Ojs\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove settings
     *
     * @param \Ojs\JournalBundle\Entity\JournalSetting $settings
     */
    public function removeSetting(\Ojs\JournalBundle\Entity\JournalSetting $settings)
    {
        $this->settings->removeElement($settings);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Remove subjects
     *
     * @param \Ojs\JournalBundle\Entity\Subject $subjects
     */
    public function removeSubject(\Ojs\JournalBundle\Entity\Subject $subjects)
    {
        $this->subjects->removeElement($subjects);
    }

    /**
     * Add bannedUsers
     *
     * @param \Ojs\UserBundle\Entity\User $bannedUsers
     * @return Journal
     */
    public function addBannedUser(\Ojs\UserBundle\Entity\User $bannedUsers)
    {
        $this->bannedUsers[] = $bannedUsers;

        return $this;
    }

    /**
     * Remove bannedUsers
     *
     * @param \Ojs\UserBundle\Entity\User $bannedUsers
     */
    public function removeBannedUser(\Ojs\UserBundle\Entity\User $bannedUsers)
    {
        $this->bannedUsers->removeElement($bannedUsers);
    }

    /**
     * Get bannedUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBannedUsers()
    {
        return $this->bannedUsers;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle() . "[{$this->getId()}]";
    }

    /**
     * @return mixed
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param UserJournalRole $role
     * @return $this
     */
    public function addUserRole(UserJournalRole $role)
    {
        $this->userRoles->add($role);
        return $this;
    }

    /**
     * @param UserJournalRole $role
     * @return $this
     */
    public function removeUserRole(UserJournalRole $role)
    {
        $this->userRoles->removeElement($role);
        return $this;
    }

    /**
     * @var string
     */
    private $logo;

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return $this
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $journals_indexs;

    /**
     * Add journalThemes
     *
     * @param \Ojs\JournalBundle\Entity\JournalTheme $journalThemes
     * @return Journal
     */
    public function addJournalTheme(\Ojs\JournalBundle\Entity\JournalTheme $journalThemes)
    {
        $this->journalThemes[] = $journalThemes;

        return $this;
    }

    /**
     * Remove journalThemes
     *
     * @param \Ojs\JournalBundle\Entity\JournalTheme $journalThemes
     */
    public function removeJournalTheme(\Ojs\JournalBundle\Entity\JournalTheme $journalThemes)
    {
        $this->journalThemes->removeElement($journalThemes);
    }

    /**
     * Add journals_indexs
     *
     * @param \Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs
     * @return Journal
     */
    public function addJournalsIndex(\Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs[] = $journalsIndexs;

        return $this;
    }

    /**
     * Remove journals_indexs
     *
     * @param \Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs
     */
    public function removeJournalsIndex(\Ojs\JournalBundle\Entity\JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs->removeElement($journalsIndexs);
    }

    /**
     * Get journals_indexs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJournalsIndexs()
    {
        return $this->journals_indexs;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $submissionChecklist;

    /**
     * Add submission checklist item
     *
     * @param \Ojs\JournalBundle\Entity\SubmissionChecklist $checklistItem
     * @return Journal
     */
    public function addSubmissionChecklist(\Ojs\JournalBundle\Entity\SubmissionChecklist $checklistItem)
    {
        $this->submissionChecklist[] = $checklistItem;

        return $this;
    }

    /**
     * Remove submission checklist item
     *
     * @param \Ojs\JournalBundle\Entity\SubmissionChecklist $item
     */
    public function removeSubmissionChecklist(\Ojs\JournalBundle\Entity\SubmissionChecklist $item)
    {
        $this->submissionChecklist->removeElement($item);
    }

    /**
     * Get submission checklist
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubmissionChecklist()
    {
        return $this->submissionChecklist;
    }

    protected $logo_options;
    protected $header_options;
    protected $image_options;

    /**
     * @return mixed
     */
    public function getImageOptions()
    {
        return $this->image_options;
    }

    /**
     * @param mixed $image_options
     */
    public function setImageOptions($image_options)
    {
        $this->image_options = $image_options;
    }

    /**
     * @return mixed
     */
    public function getHeaderOptions()
    {
        return $this->header_options;
    }

    /**
     * @param mixed $header_options
     */
    public function setHeaderOptions($header_options)
    {
        $this->header_options = $header_options;
    }

    /**
     * @return mixed
     */
    public function getLogoOptions()
    {
        return $this->logo_options;
    }

    /**
     * @param mixed $logo_options
     */
    public function setLogoOptions($logo_options)
    {
        $this->logo_options = $logo_options;
    }

    /**
     * Alias for imageoptions
     * @return mixed
     */
    public function getCoverOptions()
    {
        return $this->getImageOptions();
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /** @var  boolean */
    protected $setup_status;

    /**
     * @return boolean
     */
    public function isSetupStatus()
    {
        return $this->setup_status;
    }

    /**
     * @param boolean $setup_status
     */
    public function setSetupStatus($setup_status)
    {
        $this->setup_status = $setup_status;
    }

    /** @var  string */
    protected $footer_text;

    /**
     * @return string
     */
    public function getFooterText()
    {
        return $this->footer_text;
    }

    /**
     * @param string $footer_text
     */
    public function setFooterText($footer_text)
    {
        $this->footer_text = $footer_text;
    }

}
