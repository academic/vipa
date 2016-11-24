<?php

namespace Ojs\CoreBundle\Params;

class IssueFileParams
{
    const OTHER = 0;
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
        self::OTHER => 'other',
        self::SUPPLEMENTARY_FILE => 'workflow.supp_file',
        self::RESEARCH_METARIALS => 'workflow.research_materials',
        self::RESEARCH_RESULTS => 'workflow.research_results',
        self::TRANSCRIPTS => 'transcripts',
        self::DATA_ANALYSIS => 'article.data.analysis',
        self::DATA_SET => 'dataset',
        self::SOURCE_TEXT => 'source.text',
        self::PICTURES => 'pictures',
        self::TABLES => 'tables',
        self::COPYRIGHT_TRANSFER_FORM => 'copyright.transfer.form',
        self::COMPETING_OF_INSTEREST_FILE => 'competing.of.interest.file',
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
