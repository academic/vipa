<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * Article Author relation and some extra fields about this relation
 */
class ArticleAuthor extends \Ojstr\Common\Entity\GenericExtendedEntity {

    /**
     *
     * @var integer
     */
    private $id;

    /**
     *
     * @var integer 
     */
    private $authorOrder;

    /**
     *
     * @var \Ojstr\JournalBundle\Entity\Article 
     */
    private $article;

    /**
     *
     * @var \Ojstr\JournalBundle\Entity\Author 
     */
    private $author;

    /**
     * 
     * @return integer
     */
    public function getAuthorOrder() {
        return $this->authorOrder;
    }

    /**
     * 
     * @param integer $authorOrder
     * @return \Ojstr\JournalBundle\Entity\ArticleAuthor
     */
    public function setAuthorOrder($authorOrder) {
        $this->authorOrder = $authorOrder;
        return $this;
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
