<?php

namespace Vipa\CoreBundle\Entity;

/**
 * Class TimestampableTrait.
 */
trait TimestampableTrait
{
    /**  @var \DateTime $created */
    protected $created;

    /** @var \DateTime $updated */
    protected $updated;

    /** @var \DateTime $contentChanged */
    protected $contentChanged;

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getContentChanged()
    {
        return $this->contentChanged;
    }
}
