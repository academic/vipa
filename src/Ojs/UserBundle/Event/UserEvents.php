<?php
namespace Ojs\UserBundle\Event;

final class UserEvents
{
    const USER_INFO_CHANGE = 'ojs.user.info_changed';
    const USER_PASSWORD_CHANGE = 'ojs.user.password_changed';
    const USER_PASSWORD_RESET = 'ojs.user.password_reset';
    const USER_REGISTER = 'ojs.user.registered';
    const USER_LOGIN = 'ojs.user.logged_in';
    const USER_LOGOUT = 'ojs.user.logged_out';
}

