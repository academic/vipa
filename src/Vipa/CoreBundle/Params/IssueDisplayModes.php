<?php

namespace Vipa\CoreBundle\Params;

class IssueDisplayModes
{
    const SHOW_ALL = 0;
    const SHOW_TITLE = 1;
    const SHOW_VOLUME_AND_NUMBER = 2;

    public static function getDisplayModes()
    {
        return [
            'all'             => IssueDisplayModes::SHOW_ALL,
            'title'           => IssueDisplayModes::SHOW_TITLE,
            'volumeAndNumber' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER,
        ];
    }
}
