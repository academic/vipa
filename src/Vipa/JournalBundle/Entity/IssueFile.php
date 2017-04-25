<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Vipa\AnalyticsBundle\Entity\IssueFileStatistic;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Vipa\CoreBundle\Annotation\Display;

/**
 * IssueFile
 * @GRID\Source(columns="id,title,langCode,file.name")
 * @JMS\ExclusionPolicy("all")
 */
class IssueFile extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     * @JMS\Expose
     */
    protected $id;
    /**
     * @Prezent\Translations(targetEntity="Vipa\JournalBundle\Entity\IssueFileTranslation")
     * @JMS\Expose
     */
    protected $translations;
    /**
     * @var integer
     * @JMS\Expose
     */
    private $type;
    /**
     * @var string
     * @JMS\Expose
     * @Display\File(path="issuefiles")
     */
    private $file;
    /**
     * @var integer
     */
    private $version;
    /**
     * @var string
     */
    private $keywords;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     * @GRID\Column(title="issuefile.title")
     */
    private $title;
    /**
     * @var string
     * @GRID\Column(title="issuefile.langcode")
     * @JMS\Expose
     */
    private $langCode;
    /**
     * @var Issue
     */
    private $issue;
    /**
     * @var ArrayCollection|IssueFileStatistic[]
     */
    private $statistics;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->statistics = new ArrayCollection();
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
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return IssueFile
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return IssueFile
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set version
     *
     * @param integer $version
     * @return IssueFile
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return IssueFile
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getLogicalFieldTranslation('description', false);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return IssueFile
     */
    public function setDescription($description)
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Vipa\JournalBundle\Entity\IssueFileTranslation
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
            $translation = new IssueFileTranslation();
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getLogicalFieldTranslation('title', false);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return IssueFile
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);

        return $this;
    }

    /**
     * Get langCode
     *
     * @return string
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * Set langCode
     *
     * @param string $langCode
     * @return IssueFile
     */
    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;

        return $this;
    }

    /**
     * Get issue
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set issue
     *
     * @param Issue $issue
     * @return IssueFile
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;
        $issue->addIssueFile($this);

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return IssueFile
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return ArrayCollection|\Vipa\AnalyticsBundle\Entity\IssueFileStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|\Vipa\AnalyticsBundle\Entity\IssueFileStatistic[] $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * Returns the issue's download count
     *
     * @return int
     */
    public function getDownloadCount()
    {
        $count = 0;

        foreach ($this->statistics as $stat) {
            $count += $stat->getDownload();
        }

        return $count;
    }
}
