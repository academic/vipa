<?php

namespace Ojs\CoreBundle\Annotation\Display;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class File
{
    private $propertyName;

    private $path;

    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['propertyName'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getPath()
    {
        return $this->path;
    }
}