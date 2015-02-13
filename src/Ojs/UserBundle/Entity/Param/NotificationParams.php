<?php

namespace Ojs\UserBundle\Entity\Param;

/**
 * Notification
 */
class NotificationParams
{
    const LEVEL_INFORMATION = 1;
    const LEVEL_CONFIRMATION = 2;
    const LEVEL_WARNING = 3;
    const LEVEL_DANGER = 4;
    const LEVEL_ERROR = 5;

    /*we will generate */
    const ACTION_ASSIGN_EDITOR = 'assign as editor';
    const ACTION_ASK_FOR_REVIEW = 'ask for review';

}
