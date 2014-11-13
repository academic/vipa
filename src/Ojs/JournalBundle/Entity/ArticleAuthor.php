<?php

namespace Ojs\JournalBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Ojs\Common\Entity\GenericExtendedEntity;

/**
 * Authors of article and orders
 * @ExclusionPolicy("all")
 */
class ArticleAuthor extends GenericExtendedEntity
{
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
     * @var \Ojs\JournalBundle\Entity\Author
     * @Expose
     */
    private $author;

    /**
     * @var \Ojs\JournalBundle\Entity\Article
     *
     */
    private $article;

    /**
     *
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->author ? $this->author->getId() : false;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set authorOrder
     *
     * @param  integer       $authorOrder
     * @return ArticleAuthor
     */
    public function setAuthorOrder($authorOrder)
    {
        $this->authorOrder = $authorOrder;

        return $this;
    }

    /**
     * Get authorOrder
     *
     * @return integer
     */
    public function getAuthorOrder()
    {
        return $this->authorOrder;
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
     * @param  \Ojs\JournalBundle\Entity\Article       $article
     * @return \Ojs\JournalBundle\Entity\ArticleAuthor
     */
    public function setArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     *
     * @return \Ojs\JournalBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     *
     * @param  \Ojs\JournalBundle\Entity\Author        $author
     * @return \Ojs\JournalBundle\Entity\ArticleAuthor
     */
    public function setAuthor(\Ojs\JournalBundle\Entity\Author $author)
    {
        $this->author = $author;

        return $this;
    }

}
