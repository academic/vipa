<?php

namespace Vipa\CoreBundle\Entity;

trait SoftDeletableTrait
{

    /** @var \DateTime */
    protected $deletedAt;

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}
