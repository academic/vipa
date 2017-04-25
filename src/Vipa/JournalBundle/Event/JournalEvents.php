<?php

namespace Vipa\JournalBundle\Event;

final class JournalEvents
{
    const LISTED = 'vipa.journal.list';

    const PRE_CREATE = 'vipa.journal.pre_create';

    const POST_CREATE = 'vipa.journal.post_create';

    const PRE_UPDATE = 'vipa.journal.pre_update';

    const POST_UPDATE = 'vipa.journal.post_update';

    const PRE_DELETE = 'vipa.journal.pre_delete';

    const POST_DELETE = 'vipa.journal.post_delete';
}
