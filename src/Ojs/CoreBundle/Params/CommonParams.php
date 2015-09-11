<?php

namespace Ojs\CoreBundle\Params;

class CommonParams
{

    public static $userStatusArray = [
        0 => "Passive",
        1 => "Active",
        2 => "Banned",
    ];
    public static $journalApplicationStatusArray = [
        2 => 'application.status.waiting',
        1 => 'application.status.complete',
        0 => 'application.status.onhold',
        -1 => 'application.status.rejected',
        -2 => 'application.status.deleted',
    ];
    public static $publisherStatusArray = [
        -1 => 'application.status.rejected',
        0 => 'application.status.onhold',
        1 => 'application.status.complete',
    ];
    protected static $journalStatusArray = array(
        -3 => "status.rejected",
        -2 => "status.unpublished",
        -1 => "status.not_submitted",
        0 => "status.inreview",
        1 => "status.published",
    );

    /**
     * @return array
     */
    public static function getStatusTexts()
    {
        return self::$journalStatusArray;
    }

    /**
     * @param  string       $statusText
     * @return null|integer
     */
    public static function getStatusCode($statusText)
    {
        $i = array_search($statusText, self::$journalStatusArray);

        return $i ?: null;
    }

    public static function statusText($statusNum = null)
    {
        if (array_key_exists($statusNum, self::$journalStatusArray)) {
            return self::$journalStatusArray[$statusNum];
        } else {
            return self::$journalStatusArray;
        }
    }

    /**
     * @param  integer $statusNum
     * @return array
     */
    public static function journalApplicationStatus($statusNum)
    {
        if (array_key_exists($statusNum, self::$journalApplicationStatusArray)) {
            return self::$journalApplicationStatusArray[$statusNum];
        } else {
            return self::$journalApplicationStatusArray;
        }
    }

    /**
     * @param  integer $statusNum
     * @return array
     */
    public static function publisherStatus($statusNum)
    {
        if (array_key_exists($statusNum, self::$publisherStatusArray)) {
            return self::$publisherStatusArray[$statusNum];
        } else {
            return self::$publisherStatusArray;
        }
    }
}
