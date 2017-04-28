<?php
namespace Vipa\UserBundle\Event;

use Vipa\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserEvent
 * @package Vipa\UserBundle\Event
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
