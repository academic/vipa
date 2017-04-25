<?php

namespace Vipa\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * Class PurifiedTextareaTransformer
 * @package Vipa\CoreBundle\Form\DataTransformer
 */
class PurifiedTextareaTransformer implements DataTransformerInterface
{
    private $purifier;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->purifier = new \HTMLPurifier();
    }

    /**
     * @see Symfony\Component\Form\DataTransformerInterface::transform()
     * @param mixed $value
     * @return mixed|string
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @see Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     * @param mixed $value
     * @return mixed|string
     */
    public function reverseTransform($value)
    {
        return $this->purifier->purify($value);
    }
}
