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
use Ojs\UserBundle\Entity\User;
use Ojs\LocationBundle\Entity\Country;

/**
 * Journal
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,title,issn,eissn,country.name,institution.name")
 */
class Journal implements Translatable
{
    use GenericEntityTrait;

    /** @var  boolean */
    protected $setup_status;
    /** @var  string */
    protected $footer_text;
    /**
     * @var integer
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $id;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $title;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $titleAbbr;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $titleTransliterated;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $subtitle;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $path;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $domain;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $issn;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $eissn;
    /**
     * @var \DateTime
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $firstPublishDate;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $period;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $url;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $address;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $phone;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $email;
    /**
     * @var Country
     * @Expose
     * @Groups({"JournalDetail"})
     * @Grid\Column(field="country.name", title="country")
     */
    private $country;

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
     * @Groups({"JournalDetail"})
     */
    private $image;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
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
     * @Groups({"JournalDetail"})
     */
    private $theme;
    /**
     * @var boolean
     * @Expose
     */
    private $isConfigured;
    /**
     * @var Collection
     */
    private $users;
    /**
     * @var Collection
     * @Expose
     * @Groups({"IssueDetail"})
     */
    private $articles;
    /**
     * @var Collection
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $issues;
    /**
     * @var Collection
     */
    private $boards;
    /**
     * @var Collection
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $languages;
    /**
     * @var Collection
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $subjects;
    /**
     * @var Collection
     * @Groups({"JournalDetail"})
     */
    private $sections;
    /**
     *
     * arbitrary settings
     * @var ArrayCollection|JournalSetting[]
     */
    private $settings;
    /**
     * @var Institution
     * @Expose
     * @Groups({"JournalDetail"})
     * @Grid\Column(field="institution.name", title="institution")
     */
    private $institution;
    /**
     * @var Collection
     * @Expose
     */
    private $journalThemes;
    /**
     * @var integer
     */
    private $institutionId;
    /**
     * @var Collection
     */
    private $bannedUsers;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $description;
    /**
     * @var string
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $logo;
    /**
     * @var Collection
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $journals_indexs;
    /**
     * @var Collection
     * @Expose
     * @Groups({"JournalDetail"})
     */
    private $submissionChecklist;
    /**
     * @var int
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $view_count;
    /**
     * @var int
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $download_count;
    /**
     * @var boolean
     * @Expose
     * @Groups({"JournalDetail","IssueDetail"})
     */
    private $printed;

    protected $translations;

    /** @var Collection */
    private $journalUsers;

    /** @var ArrayCollection */
    private $journalContacts;

    /**
     * @var string
     */
    private $competingFile;

    /**
     * @var Lang
     */
    private $mandatoryLang;

    /**
     * @var integer
     */
    private $mandatoryLangId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->boards = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->journalThemes = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(JournalTranslation $t)
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
     * @param  string  $settingName
     * @param  string  $value
     * @return Journal
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
     * @param  string                 $settingName
     * @return JournalSetting|boolean
     */
    public function getAttribute($settingName)
    {
        return $this->getSetting($settingName);
    }

    /**
     *
     * @param  string                 $settingName
     * @return JournalSetting|boolean
     */
    public function getSetting($settingName)
    {
        return isset($this->settings[$settingName]) ? $this->settings[$settingName] : false;
    }

    /**
     * @param  JournalSection $section
     * @return Journal
     */
    public function addSection(JournalSection $section)
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * @param JournalSection $section
     */
    public function removeSection(JournalSection $section)
    {
        $this->sections->removeElement($section);
    }

    /**
     * @return Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param  JournalTheme $journalTheme
     * @return Journal
     */
    public function addJournalThemes(JournalTheme $journalTheme)
    {
        $this->journalThemes[] = $journalTheme;

        return $this;
    }

    /**
     * @param JournalTheme $journalTheme
     */
    public function removeJournalThemes(JournalTheme $journalTheme)
    {
        $this->journalThemes->removeElement($journalTheme);
    }

    /**
     * @return Collection
     */
    public function getJournalThemes()
    {
        return $this->journalThemes;
    }

    /**
     * @param  Lang    $language
     * @return Journal
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
     * @param  Subject $subject
     * @return Journal
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * @param Subject $subject
     */
    public function removeSubjects(Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * @return Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
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
     * @param  string  $path
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
     * @param  string  $domain
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
     * @param  string  $titleAbbr
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
     * @param  string  $titleTransliterated
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
     * @param  string  $subtitle
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
     * @param  string  $issn
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
     * @param  string  $eissn
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
     * @param  string  $period
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
     * @param  string  $url
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
     * @param  int     $institutionId
     * @return Journal
     */
    public function setInstitutionId($institutionId)
    {
        $this->institutionId = $institutionId;

        return $this;
    }

    /**
     * @return Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set institution
     * @param  Institution $institution
     * @return Journal
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;

        return $this;
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
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     * @param  Country $country
     * @return Journal
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

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
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published ? true : false;
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
     * @param  string  $image
     * @return Journal
     */
    public function setImage($image)
    {
        $this->image = $image;

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
     * Set slug
     *
     * @param  string  $slug
     * @return Journal
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
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
     * Get designId
     *
     * @return integer
     */
    public function getDesignId()
    {
        return $this->themeId;
    }

    /**
     * Set designId
     * @param $themeId
     * @return $this
     */
    public function setDesignId($themeId)
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
     * @param  Theme   $theme
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
     * @return string
     */
    public function getGoogleAnalyticsId()
    {
        return $this->googleAnalyticsId;
    }

    /**
     * Set googleAnalyticsId
     *
     * @param  string  $googleAnalyticsId
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
     * @param  Article $article
     * @return Journal
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
     * @return Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add board
     * @param  Board $board
     * @return $this
     */
    public function addBoard(Board $board)
    {
        $this->boards[] = $board;

        return $this;
    }

    /**
     * Remove board
     *
     * @param Board $board
     */
    public function removeBoard(Board $board)
    {
        $this->boards->removeElement($board);
    }

    /**
     * Get boards
     *
     * @return Collection
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add issue
     *
     * @param  Issue   $issue
     * @return Journal
     */
    public function addIssue(Issue $issue)
    {
        $this->issues[] = $issue;

        return $this;
    }

    /**
     * Remove issue
     *
     * @param Issue $issue
     */
    public function removeIssue(Issue $issue)
    {
        $this->issues->removeElement($issue);
    }

    /**
     * Get issues
     *
     * @return Collection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Remove articles
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get articles
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add articles
     *
     * @param  User    $users
     * @return Journal
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove settings
     *
     * @param JournalSetting $settings
     */
    public function removeSetting(JournalSetting $settings)
    {
        $this->settings->removeElement($settings);
    }

    /**
     * Get settings
     *
     * @return Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Remove subjects
     *
     * @param Subject $subjects
     */
    public function removeSubject(Subject $subjects)
    {
        $this->subjects->removeElement($subjects);
    }

    /**
     * Add bannedUsers
     *
     * @param  User    $bannedUsers
     * @return Journal
     */
    public function addBannedUser(User $bannedUsers)
    {
        $this->bannedUsers[] = $bannedUsers;

        return $this;
    }

    /**
     * Remove bannedUsers
     *
     * @param User $bannedUsers
     */
    public function removeBannedUser(User $bannedUsers)
    {
        $this->bannedUsers->removeElement($bannedUsers);
    }

    /**
     * Get bannedUsers
     *
     * @return Collection
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
        return $this->getTitle()."[{$this->getId()}]";
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
     * @param  string  $title
     * @return Journal
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param  string $logo
     * @return $this
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Add journalThemes
     *
     * @param  JournalTheme $journalThemes
     * @return Journal
     */
    public function addJournalTheme(JournalTheme $journalThemes)
    {
        $this->journalThemes[] = $journalThemes;

        return $this;
    }

    /**
     * Remove journalThemes
     *
     * @param JournalTheme $journalThemes
     */
    public function removeJournalTheme(JournalTheme $journalThemes)
    {
        $this->journalThemes->removeElement($journalThemes);
    }

    /**
     * Add journals_indexs
     *
     * @param  JournalsIndex $journalsIndexs
     * @return Journal
     */
    public function addJournalsIndex(JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs[] = $journalsIndexs;

        return $this;
    }

    /**
     * Remove journals_index
     *
     * @param JournalsIndex $journalsIndexs
     */
    public function removeJournalsIndex(JournalsIndex $journalsIndexs)
    {
        $this->journals_indexs->removeElement($journalsIndexs);
    }

    /**
     * Get journals_indexs
     *
     * @return Collection
     */
    public function getJournalsIndexs()
    {
        return $this->journals_indexs;
    }

    /**
     * Add submission checklist item
     *
     * @param  SubmissionChecklist $checklistItem
     * @return Journal
     */
    public function addSubmissionChecklist(SubmissionChecklist $checklistItem)
    {
        $this->submissionChecklist[] = $checklistItem;

        return $this;
    }

    /**
     * Remove submission checklist item
     *
     * @param SubmissionChecklist $item
     */
    public function removeSubmissionChecklist(SubmissionChecklist $item)
    {
        $this->submissionChecklist->removeElement($item);
    }

    /**
     * Get submission checklist
     *
     * @return Collection
     */
    public function getSubmissionChecklist()
    {
        return $this->submissionChecklist;
    }

    /**
     * @return string
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

    /**
     * @return boolean
     */
    public function getSetupStatus()
    {
        return $this->setup_status;
    }

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

    /**
     * @return int
     */
    public function getDownloadCount()
    {
        return $this->download_count;
    }

    /**
     * @param  int   $download_count
     * @return $this
     */
    public function setDownloadCount($download_count)
    {
        $this->download_count = $download_count;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->view_count;
    }

    /**
     * @param  int   $view_count
     * @return $this
     */
    public function setViewCount($view_count)
    {
        $this->view_count = $view_count;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrinted()
    {
        return $this->printed;
    }

    /**
     * @param  boolean $printed
     * @return $this
     */
    public function setPrinted($printed)
    {
        $this->printed = $printed;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getJournalUsers()
    {
        return $this->journalUsers;
    }

    /**
     * @param mixed $journalUsers
     */
    public function setJournalUsers($journalUsers)
    {
        $this->journalUsers = $journalUsers;
    }

    /**
     * @return ArrayCollection
     */
    public function getJournalContacts()
    {
        return $this->journalContacts;
    }

    /**
     * @param ArrayCollection $journalContacts
     */
    public function setJournalContacts($journalContacts)
    {
        $this->journalContacts = $journalContacts;
    }

    /**
     * @param  string $competingFile
     * @return $this
     */
    public function setCompetingFile($competingFile)
    {
        $this->competingFile = $competingFile;

        return $this;
    }

    /**
     * @return File
     */
    public function getCompetingFile()
    {
        return $this->competingFile;
    }

    /**
     * @param  Lang $mandatoryLang
     * @return $this
     */
    public function setMandatoryLang(Lang $mandatoryLang)
    {
        $this->mandatoryLang = $mandatoryLang;

        return $this;
    }

    /**
     * @return Lang
     */
    public function getMandatoryLang()
    {
        return $this->mandatoryLang;
    }

    /**
     * @param  integer $mandatoryLangId
     * @return $this
     */
    public function setMandatoryLangId($mandatoryLangId)
    {
        $this->mandatoryLangId = $mandatoryLangId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMandatoryLangId()
    {
        return $this->mandatoryLangId;
    }
}
