<?php

namespace Vipa\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * Class TagsTransformer
 * @package Vipa\CoreBundle\Form\DataTransformer
 */
class TagsTransformer implements DataTransformerInterface
{
    private $delimiter = ',';
    public function transform($tagsData)
    {
        if(empty($tagsData)) {
            return null;
        }
        $parts = explode($this->delimiter, $tagsData);

        return $parts;
    }


    public function reverseTransform($values = null)
    {
        if(empty($values) || !is_array($values)){
            return null;
        }
        return implode($this->delimiter, $values);
    }
}
