<?php
namespace Ojs\UserBundle\Event;

final class UserEvents
{
    const USER_INFO_CHANGE = 'ojs.user.info.change';
    const USER_PASSWORD_CHANGE = 'ojs.user.password.change';
    const USER_PASSWORD_RESET = 'ojs.user.password.reset.happen';
    const USER_REGISTER = 'ojs.user.register.happen';
    const USER_LOGIN = 'ojs.user.login.happen';
    const USER_LOGOUT = 'ojs.user.logout.happen';
}

