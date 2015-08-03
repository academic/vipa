<?php

namespace Ojs\Common\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * Class TagsTransformer
 * @package Ojs\Common\Form\DataTransformer
 */
class TagsTransformer implements DataTransformerInterface
{
    private $delimiter = ',';
    public function transform($tagsData)
    {
        if(is_null($tagsData)) {
            return array();
        }
        $parts = explode($this->delimiter, $tagsData);

        return $parts;
    }


    public function reverseTransform($values = null)
    {
        if(empty($values)){
            return null;
        }
        return implode ($this->delimiter, $values);
    }
}
