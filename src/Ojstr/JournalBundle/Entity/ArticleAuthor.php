<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Authors of article and orders
 * @ExclusionPolicy("all")
 */
class ArticleAuthor {

    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var integer
     * @Expose
     */
    private $authorOrder;

    /**
     * @var \Ojstr\JournalBundle\Entity\Author
     * @Expose
     */
    private $author;

    /**
     * @var \Ojstr\JournalBundle\Entity\Article
     * 
     */
    private $article;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set authorOrder
     *
     * @param integer $authorOrder
     * @return ArticleAuthor
     */
    public function setAuthorOrder($authorOrder) {
        $this->authorOrder = $authorOrder;

        return $this;
    }

    /**
     * Get authorOrder
     *
     * @return integer 
     */
    public function getAuthorOrder() {
        return $this->authorOrder;
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
     * @return \Ojstr\JournalBundle\Entity\ArticleAuthor
     */
    public function setArticle(\Ojstr\JournalBundle\Entity\Article $article) {
        $this->article = $article;
        return $this;
    }

    /**
     * 
     * @return \Ojstr\JournalBundle\Entity\Author
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * 
     * @param \Ojstr\JournalBundle\Entity\Author $author
     * @return \Ojstr\JournalBundle\Entity\ArticleAuthor
     */
    public function setAuthor(\Ojstr\JournalBundle\Entity\Author $author) {
        $this->author = $author;
        return $this;
    }

}
