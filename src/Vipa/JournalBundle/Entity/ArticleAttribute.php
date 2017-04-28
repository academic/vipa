<?php

namespace Vipa\JournalBundle\Entity;

use Gedmo\Translatable\Translatable;
use Vipa\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;

/**
 * Article key-value attributes
 */
class ArticleAttribute implements Translatable
{
    use GenericEntityTrait;

    private $article;
    private $attribute;
    private $value;
    private $id;

    public function __construct($name = null, $value = null, $article = null)
    {
        $name !== null && $this->attribute = $name;
        $value !== null && $this->value = $value;
        $article !== null && $this->article = $article;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get attribute
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set attribute
     *
     * @param  string           $attribute
     * @return ArticleAttribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param  string           $value
     * @return ArticleAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get article
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set article
     *
     * @param  Article          $article
     * @return ArticleAttribute
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }
}
