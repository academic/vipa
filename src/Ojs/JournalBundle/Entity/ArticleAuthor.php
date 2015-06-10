<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\Common\Entity\GenericEntityTrait;

/**
 * Authors of article and orders
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,author.firstName,author.lastName,article.title")
 */
class ArticleAuthor implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     * @GRID\Column(title="id")
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
     * @GRID\Column(title="firstName",field="author.firstName")
     * @GRID\Column(title="lastName",field="author.lastName")
     * @Expose
     * @Groups({"IssueDetail","ArticleDetail"})
     */
    private $author;

    /**
     * @var Article
     * @GRID\Column(title="article",field="article.title")
     */
    private $article;

    public function __construct($name = null, $value = null, Article $article = null)
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
     * @param  integer $authorOrder
     * @return $this
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
     * @param  Article $article
     * @return $this
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
     * @param  Author $author
     * @return $this
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    public function __toString()
    {
        return $this->getAuthor()->getTitle().' '.$this->getAuthor()->getFirstName().' '.$this->getAuthor(
        )->getLastName();
    }
}
