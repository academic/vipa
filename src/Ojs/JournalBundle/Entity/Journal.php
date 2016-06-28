<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as JMS;
use Ojs\AnalyticsBundle\Entity\JournalStatistic;
use Ojs\CoreBundle\Annotation\Display;
use Ojs\CoreBundle\Entity\AnalyticsTrait;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Ojs\CoreBundle\Entity\ThemeInterface;
use Ojs\CoreBundle\Params\JournalStatuses;
use Ojs\UserBundle\Entity\User;
use BulutYazilim\LocationBundle\Entity\Country;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;

/**
 * Journal
 * @JMS\ExclusionPolicy("all")
 * @GRID\Source(columns="id,translations.title,issn,eissn,status,publisher.translations.name")
 */
class Journal extends AbstractTranslatable
{
    use GenericEntityTrait;
    use AnalyticsTrait;
    /**
     * List of Journal Status
     * @var array
     */
    public static $statuses = [
        JournalStatuses::STATUS_APPLICATION  => 'status.application',
        JournalStatuses::STATUS_REJECTED     => 'status.rejected',
        JournalStatuses::STATUS_NAME_CHANGED => 'status.name_changed',
        JournalStatuses::STATUS_HOLD         => 'status.hold',
        JournalStatuses::STATUS_PREPARING    => 'status.preparing',
        JournalStatuses::STATUS_PUBLISHED    => 'status.published',
        JournalStatuses::STATUS_EXITED       => 'status.exited',
    ];
    /** @var  boolean */
    protected $setupFinished;
    /** @var  string */
    protected $footerText;
    /** @var  string */
    protected $note;
    /**
     * @var integer
     * @JMS\Expose
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\JournalTranslation")
     * @Grid\Column(field="translations.id")
     */
    protected $translations;
    /**
     * @var string
     * @JMS\Expose
     * @Grid\Column(title="Title", field="translations.title", safe=false)
     */
    private $title;
    /**
     * @var string
     * @JMS\Expose
     */
    private $titleAbbr;
    /**
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
    private $path;
    /**
     * @var string
     * @JMS\Expose
     */
    private $domain;
    /**
     * @var string
     * @JMS\Expose
     */
    private $issn;
    /**
     * @var string
     * @JMS\Expose
     */
    private $eissn;
    /**
     * @var \DateTime
     * @JMS\Expose
     */
    private $founded;
    /**
     * @var string
     * @JMS\Expose
     */
    private $url;
    /**
     * @var string
     * @JMS\Expose
     */
    private $address;
    /**
     * @var string
     * @JMS\Expose
     */
    private $phone;
    /**
     * @var string
     * @JMS\Expose
     */
    private $email;
    /**
     * @var Country
     * @JMS\Expose
     */
    private $country;
    /**
     * @var integer
     * @JMS\Expose
     * @Grid\Column(field="status", title="status", filter="select", selectFrom="values", values={
     *     "-5"="status.exited",
     *     "-4"="status.application",
     *     "-3"="status.rejected",
     *     "-2"="status.name_changed",
     *     "-1"="status.hold",
     *     "0"="status.preparing",
     *     "1"="status.published"
     * })
     */
    private $status = 0;
    /**
     * @var integer
     */
    private $accessModal;
    /**
     * @var string
     * @JMS\Expose
     * @Display\Image(filter="journal_cover")
     */
    private $image;
    /**
     * @var string
     * @JMS\Expose
     * @Display\Image(filter="journal_header")
     */
    private $header;
    /**
     * @var string
     */
    private $journalIndexesBag;
    /**
     * @var string
     * @JMS\Expose
     */
    private $googleAnalyticsId;
    /**
     * @var string
     * @JMS\Expose
     */
    private $slug;
    /**
     * @var Theme
     */
    private $theme;
    /**
     * @var Design
     */
    private $design;
    /**
     * @var boolean
     * @JMS\Expose
     */
    private $configured = false;
    /**
     * @var ArrayCollection|Article[]
     */
    private $articles;
    /**
     * @var ArrayCollection|Issue[]
     */
    private $issues;
    /**
     * @var ArrayCollection|Board[]
     */
    private $boards;
    /**
     * @var ArrayCollection|Lang[]
     * @JMS\Expose
     */
    private $languages;
    /**
     * @var ArrayCollection|Period[]
     * @JMS\Expose
     */
    private $periods;
    /**
     * @var string
     */
    private $languageCodeSet;
    /**
     * @var Collection
     * @JMS\Expose
     */
    private $subjects;
    /**
     * @var Collection
     */
    private $sections;
    /**
     * @var Publisher
     * @JMS\Expose
     * @Grid\Column(field="publisher.translations.name", title="publisher", safe=false)
     */
    private $publisher;
    /**
     * @var Collection
     */
    private $bannedUsers;
    /**
     * @var string
     * @JMS\Expose
     */
    private $description;
    /**
     * @var string
     * @JMS\Expose
     * @Display\Image(filter="index_logo")
     */
    private $logo;
    /**
     * @var ArrayCollection|JournalIndex[]
     * @JMS\Expose
     */
    private $journalIndexs;
    /**
     * @var ArrayCollection|SubmissionChecklist[]
     */
    private $submissionChecklist;
    /**
     * @var ArrayCollection|JournalSubmissionFile[]
     */
    private $journalSubmissionFiles;
    /**
     * @var ArrayCollection|JournalApplicationUploadFile[]
     */
    private $journalApplicationUploadFiles;
    /**
     * @var boolean
     * @JMS\Expose
     */
    private $printed = false;
    /**
     * Object public URI
     * @var string
     */
    private $publicURI;
    /**
     * @var Collection
     */
    private $journalUsers;
    /**
     * @var ArrayCollection
     */
    private $journalContacts;

    /**
     * @var Lang
     * @JMS\Expose
     */
    private $mandatoryLang;

    /**
     * @var ArrayCollection|JournalAnnouncement[]
     */
    private $announcements;

    /**
     * @var ArrayCollection|JournalStatistic[]
     */
    private $statistics;

    /**
     * @var
     */
    private $extraFields;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->boards = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->periods = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->journalApplicationUploadFiles = new ArrayCollection();
        $this->journalContacts = new ArrayCollection();
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
     * @param  Section $section
     * @return Journal
     */
    public function addSection(Section $section)
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setJournal($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param  Lang $language
     * @return Journal
     */
    public function addLanguage(Lang $language)
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Lang[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @return array
     */
    public function getLocaleCodeBag()
    {
        $locales = [];
        $submissionLangObjects = $this->getLanguages();
        foreach ($submissionLangObjects as $submissionLangObject) {
            $locales[] = $submissionLangObject->getCode();
        }
        return $locales;
    }

    /**
     * @param  Period $period
     * @return Journal
     */
    public function addPeriod(Period $period)
    {
        if (!$this->periods->contains($period)) {
            $this->periods->add($period);
        }

        return $this;
    }

    /**
     * @param Period $period
     */
    public function removePeriod(Period $period)
    {
        if ($this->periods->contains($period)) {
            $this->periods->removeElement($period);
        }
    }

    /**
     * @return ArrayCollection|Period[]
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * @return string
     */
    public function getLanguageCodeSet()
    {
        return $this->languageCodeSet;
    }

    /**
     * @param ArrayCollection|Lang[] $languages
     * @param $languages
     * @return $this
     */
    public function setLanguageCodeSet($languages)
    {
        $langIds = [];
        /** @var Lang $language */
        foreach ($languages as $language) {
            $langIds[] = $language->getCode();
        }
        $this->languageCodeSet = implode('-', $langIds);

        return $this;
    }

    /**
     * @param  Subject $subject
     * @return Journal
     */
    public function addSubject(Subject $subject)
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->addJournal($this);
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
            $subject->removeJournal($this);
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
        return $this->getLogicalFieldTranslation('titleAbbr', false);
    }

    /**
     * Set titleAbbr
     *
     * @param  string $titleAbbr
     * @return Journal
     */
    public function setTitleAbbr($titleAbbr)
    {
        $this->translate()->setTitleAbbr($titleAbbr);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\JournalTranslation
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
            $translation = new JournalTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setTitle($defaultTranslation->getTitle());
                $translation->setSubtitle($defaultTranslation->getSubtitle());
                $translation->setDescription($defaultTranslation->getDescription());
                $translation->setTitleAbbr($defaultTranslation->getTitleAbbr());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
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
        return $this->getLogicalFieldTranslation('subtitle', false);
    }

    /**
     * Set subtitle
     *
     * @param  string $subtitle
     * @return Journal
     */
    public function setSubtitle($subtitle)
    {
        $this->translate()->setSubtitle($subtitle);

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
     * Get founded
     *
     * @return \DateTime
     */
    public function getFounded()
    {
        return $this->founded;
    }

    /**
     * Set founded
     *
     * @param  \DateTime $founded
     * @return Journal
     */
    public function setFounded($founded)
    {
        $this->founded = $founded;

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
     * @return Publisher
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set publisher
     * @param  Publisher $publisher
     * @return Journal
     */
    public function setPublisher(Publisher $publisher)
    {
        $this->publisher = $publisher;

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
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

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
     * @param  string $image
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
     * @param  string $slug
     * @return Journal
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get theme
     *
     * @return JournalTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme
     *
     * @param  ThemeInterface $theme
     * @return Journal
     */
    public function setTheme(ThemeInterface $theme = null)
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
     * @param  string $googleAnalyticsId
     * @return Journal
     */
    public function setGoogleAnalyticsId($googleAnalyticsId)
    {
        $this->googleAnalyticsId = $googleAnalyticsId;

        return $this;
    }

    /**
     * Get configured
     * @return boolean
     */
    public function isConfigured()
    {
        return $this->configured;
    }

    /**
     * Set configured
     *
     * @param  boolean $configured
     * @return Journal
     */
    public function setConfigured($configured)
    {
        $this->configured = $configured;

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
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setJournal($this);
        }

        return $this;
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
     * Add board
     * @param  Board $board
     * @return $this
     */
    public function addBoard(Board $board)
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setJournal($this);
        }

        return $this;
    }

    /**
     * Get boards
     *
     * @return ArrayCollection|Board[]
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add issue
     *
     * @param  Issue $issue
     * @return Journal
     */
    public function addIssue(Issue $issue)
    {
        if (!$this->issues->contains($issue)) {
            $this->issues->add($issue);
            $issue->setJournal($this);
        }

        return $this;
    }

    /**
     * Get issues
     *
     * @return ArrayCollection|Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
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
     * @param Subject $subject
     * @return Journal
     */
    public function removeSubject(Subject $subject)
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
            $subject->removeJournal($this);
        }

        return $this;
    }

    /**
     * Add bannedUsers
     *
     * @param  User $bannedUser
     * @return Journal
     */
    public function addBannedUser(User $bannedUser)
    {
        $this->bannedUsers[] = $bannedUser;

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
     * @return ArrayCollection|User[]
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
        return $this->getTitle();
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getLogicalFieldTranslation('title', false);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitleTranslations()
    {
        $titles = [];
        /** @var JournalTranslation $translation */
        foreach($this->translations as $translation){
            $titles[] = $translation->getTitle(). ' ['.$translation->getLocale().']';
        }
        return implode('<br>', $titles);
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Journal
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
     * Add journalIndexs
     *
     * @param  JournalIndex $journalIndex
     * @return Journal
     */
    public function addJournalIndex(JournalIndex $journalIndex)
    {
        if (!$this->journalIndexs->contains($journalIndex)) {
            $this->journalIndexs->add($journalIndex);
            $journalIndex->setJournal($this);
        }

        return $this;
    }

    /**
     * Get journalIndexs
     *
     * @return ArrayCollection|JournalIndex[]
     */
    public function getJournalIndexs()
    {
        return $this->journalIndexs;
    }

    /**
     * Add submission checklist item
     *
     * @param  SubmissionChecklist $submissionChecklist
     * @return Journal
     */
    public function addSubmissionChecklist(SubmissionChecklist $submissionChecklist)
    {
        if (!$this->submissionChecklist) {
            $this->submissionChecklist = new ArrayCollection();
        }

        if (!$this->submissionChecklist->contains($submissionChecklist)) {
            $this->submissionChecklist->add($submissionChecklist);
            $submissionChecklist->setJournal($this);
        }

        return $this;
    }

    /**
     * Get submission checklist
     *
     * @return ArrayCollection|SubmissionChecklist[]
     */
    public function getSubmissionChecklist()
    {
        return $this->submissionChecklist;
    }

    /**
     * Add submission file item
     *
     * @param  JournalSubmissionFile $journalSubmissionFile
     * @return Journal
     */
    public function addJournalSubmissionFile(JournalSubmissionFile $journalSubmissionFile)
    {
        if (!$this->journalSubmissionFiles->contains($journalSubmissionFile)) {
            $this->journalSubmissionFiles->add($journalSubmissionFile);
            $journalSubmissionFile->setJournal($this);
        }

        return $this;
    }

    /**
     * Get submission file
     *
     * @return ArrayCollection|JournalSubmissionFile[]
     */
    public function getJournalSubmissionFiles()
    {
        return $this->journalSubmissionFiles;
    }

    /**q
     * Add application file item
     *
     * @param  JournalApplicationUploadFile $journalApplicationUploadFile
     * @return Journal
     */
    public function addJournalApplicationUploadFile(JournalApplicationUploadFile $journalApplicationUploadFile)
    {
        if (!$this->journalApplicationUploadFiles->contains($journalApplicationUploadFile)) {
            $this->journalApplicationUploadFiles->add($journalApplicationUploadFile);
            $journalApplicationUploadFile->setJournal($this);
        }

        return $this;
    }

    /**
     * Get application files
     *
     * @return ArrayCollection|JournalApplicationUploadFile[]
     */
    public function getJournalApplicationUploadFiles()
    {
        return $this->journalApplicationUploadFiles;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getLogicalFieldTranslation('description', false);
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);
    }

    /**
     * @return boolean
     */
    public function isSetupFinished()
    {
        return $this->setupFinished;
    }

    /**
     * @param boolean $setupFinished
     */
    public function setSetupFinished($setupFinished)
    {
        $this->setupFinished = $setupFinished;
    }

    /**
     * @return string
     */
    public function getFooterText()
    {
        return $this->footerText;
    }

    /**
     * @param string $footerText
     */
    public function setFooterText($footerText)
    {
        $this->footerText = $footerText;
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
     * @return Lang
     */
    public function getMandatoryLang()
    {
        return $this->mandatoryLang;
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
     * @return ArrayCollection|JournalAnnouncement[]
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param ArrayCollection|JournalAnnouncement[] $announcements
     */
    public function setAnnouncements($announcements)
    {
        $this->announcements = $announcements;
    }

    /**
     * @return Design
     */
    public function getDesign()
    {
        return $this->design;
    }

    /**
     * @param Design $design
     */
    public function setDesign(Design $design = null)
    {
        $this->design = $design;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Journal
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
     * @return Journal
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add journalUser
     *
     * @param JournalUser $journalUser
     *
     * @return Journal
     */
    public function addJournalUser(JournalUser $journalUser)
    {
        if (!$this->journalUsers->contains($journalUser)) {
            $this->journalUsers->add($journalUser);
            $journalUser->setJournal($this);
        }

        return $this;
    }

    /**
     * Add journalContact
     *
     * @param JournalContact $journalContact
     *
     * @return Journal
     */
    public function addJournalContact(JournalContact $journalContact)
    {
        if (!$this->journalContacts->contains($journalContact)) {
            $this->journalContacts->add($journalContact);
            $journalContact->setJournal($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|JournalStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|JournalStatistic[] $statistics
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
    public function getAccessModal()
    {
        return $this->accessModal;
    }

    /**
     * @param int $accessModal
     * @return $this
     */
    public function setAccessModal($accessModal)
    {
        $this->accessModal = $accessModal;

        return $this;
    }

    /**
     * @return string
     */
    public function getJournalIndexesBag()
    {
        return $this->journalIndexesBag;
    }

    /**
     * @param string $journalIndexesBag
     * @return $this
     */
    public function setJournalIndexesBag($journalIndexesBag)
    {
        $this->journalIndexesBag = $journalIndexesBag;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }

    /**
     * @param mixed $extraFields
     *
     * @return $this
     */
    public function setExtraFields($extraFields)
    {
        $this->extraFields = $extraFields;

        return $this;
    }

    /**
     * Returns true if journal is indexable
     *
     * @return bool
     */
    public function isIndexable()
    {
        if($this->getStatus() == JournalStatuses::STATUS_PUBLISHED || $this->getStatus() == JournalStatuses::STATUS_NAME_CHANGED){
            return true;
        }
        return false;
    }
}
