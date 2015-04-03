<?php

namespace Ojs\Common\Params;

class ArticleFileParams {

    const FULL_TEXT=0;
    const SUPPLEMENTARY_FILE=1;
    const RESEARCH_METARIALS=2;
    const RESEARCH_RESULTS=3;
    const TRANSCRIPTS=4;
    const DATA_ANALYSIS=5;
    const DATA_SET=6;
    const SOURCE_TEXT=7;
    const PICTURES=8;
    const TABLES=9;
    const COPYRIGHT_TRANSFER_FORM=10;
    const COMPETING_OF_INSTEREST_FILE=11;
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
        11 => 'Competing of Interest File',
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
