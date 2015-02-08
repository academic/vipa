<?php

namespace Ojs\Common\Params;

class ArticleFileParams
{

    /**
     *
     * @var array
     */
    public static $FILE_TYPES = array(
        0 => 'Full Text',
        1 => 'Supplementary File',
        2 => 'Research Materials',
        3 => 'Research Results',
        4 => 'Transcripts',
        5 => 'Data Analysis',
        6 => 'Data Set',
        7 => 'Source Text',
        8 => 'Pictures',
        9 => 'Tables',
        10 => 'Copyright Transfer Form',
    );

    /**
     * 
     * @param integer $typeNum
     * @return string
     */
    public static function fileType($typeNum)
    {
        return isset(self::$FILE_TYPES[$typeNum]) ? self::$FILE_TYPES[$typeNum] : null;
    }

}
