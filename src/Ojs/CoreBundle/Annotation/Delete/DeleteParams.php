<?php

namespace Ojs\CoreBundle\Annotation\Delete;

/**
 * @Annotation
 * @Target("CLASS")
 */
class DeleteParams
{
    /**
     * @var boolean
     */
    private $hardDelete;

    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }
    }

    /**
     * @return boolean
     */
    public function getHardDelete()
    {
        return $this->hardDelete;
    }
}