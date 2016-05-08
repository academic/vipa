<?php

namespace Ojs\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class MultipleEmailsTransformer implements DataTransformerInterface
{

    /**
     * @param array $value
     * @return string
     */
    public function transform($value)
    {
        return implode(',', $value);
    }
    /**
     * @param string $value
     * @return array
     */
    public function reverseTransform($value)
    {
        return array_map('trim', explode(',', $value));
    }
}
