<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\Common\Entity\GenericEntityTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * IssueFile
 * @GRID\Source(columns="id,title,langCode,file.name")
 * @ExclusionPolicy("all")
 */
class IssueFile
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="id")
     */
    private $id;

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

    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(IssueFileTranslation $t)
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
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return IssueFile
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

    /**
     * Remove translation
     *
     * @param \Ojs\JournalBundle\Entity\IssueFileTranslation $translation
     */
    public function removeTranslation(\Ojs\JournalBundle\Entity\IssueFileTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }
}
