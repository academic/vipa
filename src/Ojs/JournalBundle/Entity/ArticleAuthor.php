<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Gedmo\Translatable\Translatable;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Authors of article and orders
 * @ExclusionPolicy("all")
 * @GRID\Source(columns="id,author.firstName,author.lastName,article,authorOrder")
 */
class ArticleAuthor implements Translatable
{
    use GenericEntityTrait;
    /**
     * @var integer
     * @Expose
     * @GRID\Column(title="id")
     */
    private $id;

    /**
     * @var integer
     * @Expose
     */
    private $authorOrder;

    /**
     * @var Author
     * @Expose
     * @GRID\Column(title="firstName",field="author.firstName")
     * @GRID\Column(title="lastName",field="author.lastName")
     * @Expose
     */
    private $author;

    /**
     * @var Article
     * @GRID\Column(title="article")
     */
    private $article;

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
     * Get authorOrder
     *
     * @return integer
     */
    public function getAuthorOrder()
    {
        return $this->authorOrder;
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
    public function setArticle(Article $article = null)
    {
        $this->article = $article;
        if(!is_null($article)){
            $article->addArticleAuthor($this);
        }
        return $this;
    }

    public function __toString()
    {
        return $this->getAuthor()->getTitle().' '.$this->getAuthor()->getFirstName().' '.$this->getAuthor(
        )->getLastName();
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
}
