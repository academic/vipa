<?php

namespace Vipa\CoreBundle\Params;

class ArticleFileParams
{

    const FULL_TEXT = 0;
    const SUPPLEMENTARY_FILE = 1;
    const RESEARCH_METARIALS = 2;
    const RESEARCH_RESULTS = 3;
    const TRANSCRIPTS = 4;
    const DATA_ANALYSIS = 5;
    const DATA_SET = 6;
    const SOURCE_TEXT = 7;
    const PICTURES = 8;
    const TABLES = 9;
    const COPYRIGHT_TRANSFER_FORM = 10;
    const COMPETING_OF_INSTEREST_FILE = 11;
    /**
     *
     * @var array
     */
    public static $FILE_TYPES = array(
        -1 => 'submission.file',
        0 => 'workflow.full_text',
        1 => 'workflow.supp_file',
        2 => 'workflow.research_materials',
        3 => 'workflow.research_results',
        4 => 'Transcripts',
        5 => 'article.data_analysis',
        6 => 'Data Set',
        7 => 'suppfiles.source',
        8 => 'Pictures',
        9 => 'Tables',
    );

    /**
     *
     * @param  integer $typeNum
     * @return string
     */
    public static function fileType($typeNum)
    {
        return isset(self::$FILE_TYPES[$typeNum]) ? self::$FILE_TYPES[$typeNum] : null;
    }
}
