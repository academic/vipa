<?php

namespace Vipa\CoreBundle\Params;

class ArticleStatuses
{
    const STATUS_EARLY_PREVIEW = -5;
    const STATUS_WITHDRAWN = -4;
    const STATUS_REJECTED = -3;
    const STATUS_PUBLISH_READY = -2;
    const STATUS_NOT_SUBMITTED = -1;
    const STATUS_INREVIEW = 0;
    const STATUS_PUBLISHED = 1;
}
