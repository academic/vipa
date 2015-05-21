<?php

namespace Ojs\Common\Params;

class CommonParams
{

    public static $userStatusArray = [
        0 => "Passive",
        1 => "Active",
        2 => "Banned"
    ];

    protected static $statusArray = array(
        -3 => "status.rejected",
        -2 => "status.unpublished",
        -1 => "status.not_submitted",
         0 => "status.waiting",
         1 => "status.inreview",
         2 => "status.editing",
         3 => "status.published"
    );

    protected static $statusColorArray = array(
        -3 => '#FF2924',
        -2 => '#FF4724',
        -1 => '#9A9',
         0 => '#E8CC56',
         1 => '#AD55E8',
         2 => '#43FFCC',
         3 => '#3FFF23'
    );

    public static $journalApplicationStatusArray = [
         2 => 'application.status.waiting',
         1 => 'application.status.complete',
         0 => 'application.status.onhold',
        -1 => 'application.status.rejected',
        -2 => 'application.status.deleted',
    ];

    public static $institutionApplicationStatusArray = [
        0 => 'application.status.onhold',
        1 => 'application.status.rejected',
    ];

    /**
     * @return array
     */
    public static function getStatusColors()
    {
        return self::$statusColorArray;
    }

    /**
     * @return array
     */
    public static function getStatusTexts()
    {
        return self::$statusArray;
    }

    /**
     *
     * @param type $statusText
     * @return type
     */
    public static function getStatusCode($statusText)
    {
        $i = array_search($statusText, self::$statusArray);
        return $i ?: null;
    }

    public static function statusText($statusNum = null)
    {
        if(array_key_exists($statusNum, self::$statusArray)) {
            return self::$statusArray[$statusNum];
        } else {
            return self::$statusArray;
        }
    }

    /**
     * Return color of this status via status code
     * @param int $statusNum
     * @return string
     */
    public static function statusColor($statusNum)
    {
        return isset(self::$statusColorArray[$statusNum]) ? self::$statusColorArray[$statusNum] : null;
    }

    /**
     * @param integer $statusNum
     * @return array
     */
    public static function journalApplicationStatus($statusNum)
    {
        if(array_key_exists($statusNum, self::$journalApplicationStatusArray)) {
            return self::$journalApplicationStatusArray[$statusNum];
        } else {
            return self::$journalApplicationStatusArray;
        }
    }

    /**
     * @param integer $statusNum
     * @return array
     */
    public static function institutionApplicationStatus($statusNum)
    {
        if(array_key_exists($statusNum, self::$institutionApplicationStatusArray)) {
            return self::$institutionApplicationStatusArray[$statusNum];
        } else {
            return self::$institutionApplicationStatusArray;
        }
    }
}
