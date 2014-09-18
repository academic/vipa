<?php

namespace Ojstr\Common\Params;

class ArticleFileParams
{
    public static $FILE_TYPES = array(
        0 => 'Research Instrument',
        1 => 'Research Materials',
        2 => 'Research Results',
        3 => 'Transcripts',
        4 => 'Data Analysis',
        5 => 'Data Set',
        6 => 'Source Text'
    );

    public static function fileType($typeNum)
    {
        return isset(self::$FILE_TYPES[$typeNum]) ? self::$FILE_TYPES[$typeNum] : null;
    }

}
