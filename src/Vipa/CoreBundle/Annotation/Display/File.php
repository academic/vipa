<?php

namespace Vipa\CoreBundle\Annotation\Display;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class File
{
    private $path;

    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    public function getPath()
    {
        return $this->path;
    }
}