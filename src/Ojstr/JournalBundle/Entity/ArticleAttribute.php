<?php

namespace Ojstr\JournalBundle\Entity;

use \Ojstr\Common\Entity\GenericExtendedEntity;

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

}
