<?php

namespace Vipa\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Vipa\AnalyticsBundle\Entity\ArticleFileStatistic;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Vipa\CoreBundle\Annotation\Display;

/**
 * ArticleFile
 * @GRID\Source(columns="id,title,type,version,langcode")
 */
class ArticleFile
{
    use GenericEntityTrait;

    /**
     * @var integer
     * @GRID\Column(title="articlefile.id")
     */
    private $id;
    /**
     * @var integer
     * @GRID\Column(title="articlefile.type")
     */
    private $type;
    /**
     * @var string
     * @Display\File(path="articlefiles")
     */
    private $file;
    /**
     * @var integer
     * @GRID\Column(title="articlefile.version")
     */
    private $version = 0;
    /**
     * @var Article
     */
    private $article;
    /**
     *
     * @var string
     */
    private $keywords = null;
    /**
     *
     * @var string
     */
    private $description = null;
    /**
     *
     * @var string
     * @GRID\Column(title="articlefile.title")
     */
    private $title = null;
    /**
     *
     * @var string
     * @GRID\Column(title="articlefile.langcode")
     */
    private $langCode;
    /**
     * @var ArrayCollection|ArticleFileStatistic[]
     */
    private $statistics;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  integer     $type
     * @return ArticleFile
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string      $title
     * @return ArticleFile
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  string      $description
     * @return ArticleFile
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * @param  string      $keywords
     * @return ArticleFile
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    /**
     * @param  string      $langCode
     * @return ArticleFile
     */
    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;

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
     * @param  string     $file
     * @return ArticleFile
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
     * @param  integer     $version
     * @return ArticleFile
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     *
     * @param  Article     $article
     * @return ArticleFile
     */
    public function setArticle(Article $article = null)
    {
        $this->article = $article;
        if(!is_null($article)){
            $article->addArticleFile($this);
        }

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return ArticleFile
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return ArrayCollection|\Vipa\AnalyticsBundle\Entity\ArticleFileStatistic[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|\Vipa\AnalyticsBundle\Entity\ArticleFileStatistic[] $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }
    
    /**
     * Returns the article's download count
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
