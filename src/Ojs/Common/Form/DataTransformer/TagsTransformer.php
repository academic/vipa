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
        $parts = explode($this->delimiter, $tagsData);
        //$fields = array_combine($parts, $parts);

        return $parts;
    }


    public function reverseTransform($values)
    {
        return implode ($this->delimiter, $values);
    }
}
