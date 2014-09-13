<?php

namespace Ojstr\Common\Params;

class ArticleParams extends CommonParams
{
    /**
     * statusArray can be modifed like :
     *
     * public static function statusText($statusNum) {
     * self::$statusArray[-1 ] = "Deleted";
     * return isset(self::$statusArray[$statusNum]) ? self::$statusArray[$statusNum] : NULL;
     * }
     *
     */
}
