<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 6.02.15
 * Time: 16:09
 */

namespace Ojs\UserBundle\Event;


use Ojs\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
} 