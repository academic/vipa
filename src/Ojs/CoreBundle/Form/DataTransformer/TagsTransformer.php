<?php

namespace Ojs\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsTransformer
 * @package Ojs\CoreBundle\Form\DataTransformer
 */
class TagsTransformer implements DataTransformerInterface
{
    private $delimiter = ';';

    public function transform($tagsData)
    {
        return empty($tagsData) ? null : explode($this->delimiter, $tagsData);
    }

    public function reverseTransform($values = null)
    {
        return empty($values) || !is_array($values) ? null : implode($this->delimiter, $values);
    }
}
