<?php

namespace Ojs\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use GoDisco\AclTreeBundle\Annotation\AclParent;
use JMS\Serializer\Annotation as JMS;
use Ojs\Common\Entity\GenericEntityTrait;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Authors of article and orders
 * @JMS\ExclusionPolicy("all")
 * @GRID\Source(columns="id,author.firstName,author.lastName,article.title")
 */
class ArticleAuthor implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @JMS\Expose
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
     * @AclParent
     * @GRID\Column(title="firstName",field="author.firstName")
     * @GRID\Column(title="lastName",field="author.lastName")
     */
    private $author;

    /**
     * @var Article
     * @AclParent
     * @GRID\Column(title="article",field="article.title")
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
