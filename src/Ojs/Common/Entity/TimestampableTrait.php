<?php
/**
 * Created by PhpStorm.
 * User: ismailbaskin
 * Date: 22/05/15
 * Time: 04:15
 */
namespace Ojs\Common\Entity;

trait TimestampableTrait
{

    /**  @var \DateTime $created */
    protected $created;

    /** @var \DateTime $updated */
    protected $updated;

    /** @var \DateTime $contentChanged */
    protected $contentChanged;

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getContentChanged()
    {
        return $this->contentChanged;
    }
}
