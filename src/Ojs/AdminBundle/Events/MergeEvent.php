<?php

namespace Ojs\AdminBundle\Events;

use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class MergeEvent extends Event
{

    /**
     * @var User
     */
    protected $primaryUser;

    /**
     * @var User[]
     */
    protected $slaveUsers;

    public function __construct($primaryUser = null, $slaveUsers = [])
    {
        $this->primaryUser = $primaryUser;
        $this->slaveUsers = $slaveUsers;
    }

    /**
     * @return User
     */
    public function getPrimaryUser()
    {
        return $this->primaryUser;
    }

    /**
     * @return User[]
     */
    public function getSlaveUsers()
    {
        return $this->slaveUsers;
    }

}
