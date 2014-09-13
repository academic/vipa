<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleFile
 */
class ArticleFile {

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
     * @var \Ojstr\JournalBundle\Entity\Article
     * 
     */
    private $article;

    /**
     *
     * @var File
     */
    private $file;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return ArticleFile
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set fileId
     *
     * @param integer $fileId
     * @return ArticleFile
     */
    public function setFileId($fileId) {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return integer 
     */
    public function getFileId() {
        return $this->fileId;
    }

    /**
     * Set articleId
     *
     * @param integer $articleId
     * @return ArticleFile
     */
    public function setArticleId($articleId) {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Set version
     *
     * @param integer $version
     * @return ArticleFile
     */
    public function setVersion($version) {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * 
     * @return integer
     */
    public function getArticleId() {
        return $this->article ? $this->article->getId() : FALSE;
    }

    /**
     * 
     * @return \Ojstr\JournalBundle\Entity\Article 
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * 
     * @param \Ojstr\JournalBundle\Entity\Article $article
     * @return \Ojstr\JournalBundle\Entity\ArticleFile
     */
    public function setArticle(\Ojstr\JournalBundle\Entity\Article $article) {
        $this->article = $article;
        return $this;
    }

    /**
     * 
     * @return \Ojstr\JournalBundle\Entity\File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * 
     * @param \Ojstr\JournalBundle\Entity\File $file
     * @return \Ojstr\JournalBundle\Entity\ArticleFile
     */
    public function setfile(\Ojstr\JournalBundle\Entity\File $file) {
        $this->file = $file;
        return $this;
    }

}
