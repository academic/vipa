<?php

namespace Ojs\JournalBundle\Entity;

use \Ojs\Common\Entity\GenericExtendedEntity;

/**
 * Article key-value attributes
 */
class ArticleAttribute extends GenericExtendedEntity
{

    private $article;
    private $attribute;
    private $value;

    public function __construct($name, $value, $article)
    {
        $this->attribute = $name;
        $this->value = $value;
        $this->article = $article;
    }


    /**
     * Set attribute
     *
     * @param string $attribute
     * @return ArticleAttribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
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
     * Set value
     *
     * @param string $value
     * @return ArticleAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;

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
     * Set article
     *
     * @param \Ojs\JournalBundle\Entity\Article $article
     * @return ArticleAttribute
     */
    public function setArticle(\Ojs\JournalBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Ojs\JournalBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
}
