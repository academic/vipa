<?php
/**
 * Date: 26.01.15
 * Time: 16:19
 */

namespace Ojs\Common\Event;


use Doctrine\Common\Collections\ArrayCollection;
use Ojs\UserBundle\Entity\User;

class Item {
    /** @var  ArrayCollection */
    protected $object;
    /** @var  User */
    protected $actor;
    /** @var  \DateTime */
    protected $time;

    /**
     * @return ArrayCollection
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param ArrayCollection $object
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return User
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * @param User $actor
     * @return $this
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }


}