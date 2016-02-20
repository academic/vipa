<?php

namespace Ojs\UserBundle\Event;

use Ojs\CoreBundle\Events\MailEventsInterface;

final class UserEvents implements MailEventsInterface
{
    const USER_PASSWORD_RESET = 'ojs.user.password_reset';
    const USER_LOGIN = 'ojs.user.logged_in';
    const USER_LOGOUT = 'ojs.user.logged_out';

    public function getMailEventsOptions()
    {
        return [];
    }
}

