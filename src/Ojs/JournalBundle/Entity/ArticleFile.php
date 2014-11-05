<?php

namespace Ojs\JournalBundle\Entity;

use \Ojs\Common\Entity\GenericExtendedEntity;

;

/**
 * ArticleFile
 */
class ArticleFile extends GenericExtendedEntity
{

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
     * @var \Ojs\JournalBundle\Entity\Article
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
     * @return \Ojs\JournalBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\Article     $article
     * @return \Ojs\JournalBundle\Entity\ArticleFile
     */
    public function setArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     *
     * @return \Ojs\JournalBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\File        $file
     * @return \Ojs\JournalBundle\Entity\ArticleFile
     */
    public function setfile(\Ojs\JournalBundle\Entity\File $file)
    {
        $this->file = $file;

        return $this;
    }

}
