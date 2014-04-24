<?php

namespace Ojstr\JournalBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Keyword
 */
class Keyword extends \Ojstr\Entity\TimestampableEntity {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var integer
     */
    private $langId;

    /**
     * @var datetime $created 
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var datetime $updated
     * @Gedmo\Timestampable
     */
    private $updated;

    /**
     * @var datetime $contentChanged
     * @Gedmo\Timestampable()
     */
    private $contentChanged;

    public function getUpdated() {
        return $this->updated;
    }

    public function getContentChanged() {
        return $this->contentChanged;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return Keyword
     */
    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string 
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * Set langId
     *
     * @param integer $langId
     * @return Keyword
     */
    public function setLangId($langId) {
        $this->langId = $langId;
        return $this;
    }

    /**
     * Get langId
     *
     * @return integer 
     */
    public function getLangId() {
        return $this->langId;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $articles;

    /**
     * Constructor
     */
    public function __construct() {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add articles
     *
     * @param \Ojstr\JournalBundle\Entity\Article $articles
     * @return Keyword
     */
    public function addArticle(\Ojstr\JournalBundle\Entity\Article $articles) {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Ojstr\JournalBundle\Entity\Article $articles
     */
    public function removeArticle(\Ojstr\JournalBundle\Entity\Article $articles) {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles() {
        return $this->articles;
    }

}
