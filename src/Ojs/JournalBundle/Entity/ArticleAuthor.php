<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation as JMS;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * Authors of article and orders
 * @JMS\ExclusionPolicy("all")
 */
class ArticleAuthor implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @JMS\Expose
     */
    private $id;

    /**
     * @var integer
     * @JMS\Expose
     */
    private $authorOrder;

    /**
     * @var Author
     * @JMS\Expose
     */
    private $author;

    /**
     * @var Article
     *
     */
    private $article;

    public function __construct($name = null, $value = null, $article = null)
    {
        $this->attribute = $name;
        $this->value = $value;
        $this->article = $article;
    }

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
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     *
     * @param  Article       $article
     * @return ArticleAuthor
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     *
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     *
     * @param  Author        $author
     * @return ArticleAuthor
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    public function __toString()
    {
        return $this->getAuthor()->getTitle().' '.$this->getAuthor()->getFirstName().' '.$this->getAuthor()->getLastName();
    }
}
