<?php

namespace Vipa\CoreBundle\Params;

class JournalStatuses
{
    const STATUS_EXITED = -5;
    const STATUS_APPLICATION = -4;
    const STATUS_REJECTED = -3;
    const STATUS_NAME_CHANGED = -2;
    const STATUS_HOLD = -1;
    const STATUS_PREPARING = 0;
    const STATUS_PUBLISHED = 1;
}
