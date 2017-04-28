<?php

namespace Vipa\UserBundle\Event;

use FOS\UserBundle\FOSUserEvents;
use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class UserEvents implements MailEventsInterface
{
    const USER_PASSWORD_RESET = 'vipa.user.password_reset';
    const USER_LOGIN = 'vipa.user.logged_in';
    const USER_LOGOUT = 'vipa.user.logged_out';

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

