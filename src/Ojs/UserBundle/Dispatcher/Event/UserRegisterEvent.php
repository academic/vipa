<?php
/**
 * Date: 26.01.15
 * Time: 21:38
 */

namespace Ojs\UserBundle\Dispatcher\Event;


use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserRegisterEvent extends Event
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}