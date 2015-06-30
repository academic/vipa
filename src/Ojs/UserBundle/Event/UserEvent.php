<?php
namespace Ojs\UserBundle\Event;

use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEvent
 * @package Ojs\UserBundle\Event
 */
class UserEvent extends Event
{
    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
