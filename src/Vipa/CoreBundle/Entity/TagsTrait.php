<?php

namespace Vipa\CoreBundle\Entity;

trait TagsTrait
{
    protected $tags = '';

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param  string $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}
