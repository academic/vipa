<?php

namespace Ojs\JournalBundle\Event;

final class JournalEvents
{
    const LISTED = 'ojs.journal.list';

    const PRE_CREATE = 'ojs.journal.pre_create';

    const POST_CREATE = 'ojs.journal.post_create';

    const PRE_UPDATE = 'ojs.journal.pre_update';

    const POST_UPDATE = 'ojs.journal.post_update';

    const PRE_DELETE = 'ojs.journal.pre_delete';

    const POST_DELETE = 'ojs.journal.post_delete';
}
