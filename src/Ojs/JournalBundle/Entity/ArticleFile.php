<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\Common\Entity\GenericEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;

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
     */
    private $file;

    /**
     * @var integer
     * @GRID\Column(title="articlefile.version")
     */
    private $version;

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

    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(ArticleFileTranslation $t)
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
     * @param  integer     $type
     * @return ArticleFile
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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
    public function getTitle()
    {
        return $this->title;
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
    public function getDescription()
    {
        return $this->description;
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
    public function getKeywords()
    {
        return $this->keywords;
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
     * @return string
     */
    public function getLangCode()
    {
        return $this->langCode;
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
     * @param  integer     $version
     * @return ArticleFile
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
    public function setArticle(Article $article)
    {
        $this->article = $article;
        $article->addArticleFile($this);

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
}
