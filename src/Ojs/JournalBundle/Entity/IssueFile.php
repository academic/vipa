<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\Common\Entity\GenericEntityTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Doctrine\Common\Collections\ArrayCollection;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use Ojs\JournalBundle\Entity\ArticleTypesTranslation;

/**
 * IssueFile
 * @GRID\Source(columns="id,title,langCode,file.name")
 * @ExclusionPolicy("all")
 */
class IssueFile extends AbstractTranslatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    protected $id;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var string
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
     */
    private $langCode;

    /**
     * @var Issue
     */
    private $issue;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\IssueFileTranslation")
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\IssueFileTranslation
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
            if(!is_null($defaultTranslation)){
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
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
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
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
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
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
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
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
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->translate()->getDescription();
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
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
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
     * Get langCode
     *
     * @return string 
     */
    public function getLangCode()
    {
        return $this->langCode;
    }



    /**
     * Set issue
     *
     * @param Issue $issue
     * @return IssueFile
     */
    public function setIssue(Issue $issue = null)
    {
        $this->issue = $issue;

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
}
