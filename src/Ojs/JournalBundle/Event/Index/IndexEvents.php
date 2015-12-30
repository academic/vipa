<?php

namespace Ojs\JournalBundle\Event\Index;

final class IndexEvents
{
    const LISTED = 'ojs.index.list';

    const PRE_CREATE = 'ojs.index.pre_create';

    const POST_CREATE = 'ojs.index.post_create';

    const PRE_UPDATE = 'ojs.index.pre_update';

    const POST_UPDATE = 'ojs.index.post_update';

    const PRE_DELETE = 'ojs.index.pre_delete';

    const POST_DELETE = 'ojs.index.post_delete';
}
