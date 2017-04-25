<?php

namespace Vipa\CoreBundle\Entity;

trait BlameableTrait
{

    /** @var string */
    protected $createdBy = "";

    /** @var string */
    protected $updatedBy = "";

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
