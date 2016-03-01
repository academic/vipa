<?php

namespace Ojs\UserBundle\Event;

use FOS\UserBundle\FOSUserEvents;
use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class UserEvents implements MailEventsInterface
{
    const USER_PASSWORD_RESET = 'ojs.user.password_reset';
    const USER_LOGIN = 'ojs.user.logged_in';
    const USER_LOGOUT = 'ojs.user.logged_out';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(FOSUserEvents::REGISTRATION_COMPLETED, 'admin', [
                'user.username', 'user.mail', 'user.fullName',
            ]),
            new EventDetail(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, 'admin', [
                'user.username', 'user.mail', 'user.fullName',
            ]),
            new EventDetail(FOSUserEvents::PROFILE_EDIT_COMPLETED, 'admin', [
                'user.username', 'user.mail', 'user.fullName',
            ]),
        ];
    }
}

