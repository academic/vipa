<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * Article Extra Attribute
 */
class ArticleAttribute extends \Ojstr\Common\Entity\GenericExtendedEntity {

    private $article;
    private $attribute;
    private $value;

    public function __construct($name, $value, $article) {
        $this->attribute = $name;
        $this->value = $value;
        $this->article = $article;
    }

}
