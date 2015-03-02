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
        -3 => "Rejected",
        -2 => "Unpublished",
        -1 => "Not Submitted",
        0 => "Waiting",
        1 => "Reviewing",
        2 => "Editing",
        3 => "Published"
    );
    protected static $statusColorArray = array(
        -3 => '#FF2924',
        -2 => '#FF4724',
        -1 => '#9a9',
        0 => '#E8CC56',
        1 => '#AD55E8',
        2 => '#43FFCC',
        3 => '#3FFF23'
    );

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
        return $statusNum ? (isset(self::$statusArray[$statusNum]) ? self::$statusArray[$statusNum] : null) : self::$statusArray;
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

}
