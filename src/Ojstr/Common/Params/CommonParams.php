<?php

namespace Ojstr\Common\Params;

class CommonParams {

    /**
     * @return string Status description
     * @param integer $status
     */
    protected static $statusArray = array(
        0 => "Waiting",
        1 => "Published",
        2 => "Unpublished",
        3 => "Params"
    );

    public static function statusText($statusNum) {
        return isset(self::$statusArray[$statusNum]) ? self::$statusArray[$statusNum] : NULL;
    }

}
