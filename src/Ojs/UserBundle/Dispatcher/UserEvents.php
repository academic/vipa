<?php
/** 
 * Date: 26.01.15
 * Time: 21:21
 */

namespace Ojs\UserBundle\Dispatcher;


final class UserEvents {
    /**
     * This event trigged after user registration complete
     */
    const USER_REGISTER_COMPLETE='ojs_user.register.complete';
    const USER_CHANGE_PASSWORD_COMPLETE='ojs_user.change_password.complete';
    const USER_PROFILE_UPDATE='ojs_user.profile.update';
}