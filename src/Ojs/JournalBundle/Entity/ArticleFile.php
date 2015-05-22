<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * ArticleFile
 */
class ArticleFile implements Translatable
{
    use GenericEntityTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var integer
     */
    private $fileId;

    /**
     * @var integer
     */
    private $articleId;

    /**
     * @var integer
     */
    private $version;

    /**
     * @var Article
     *
     */
    private $article;

    /**
     *
     * @var File
     */
    private $file;

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
     */
    private $title = null;

    /**
     *
     * @var string
     */
    private $langCode;

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
     * Set fileId
     *
     * @param  integer     $fileId
     * @return ArticleFile
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return integer
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set articleId
     *
     * @param  integer     $articleId
     * @return ArticleFile
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
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
     * @return integer
     */
    public function getArticleId()
    {
        return $this->article ? $this->article->getId() : false;
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

        return $this;
    }

    /**
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     *
     * @param  File        $file
     * @return ArticleFile
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }
}
