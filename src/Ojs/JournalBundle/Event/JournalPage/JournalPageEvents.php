<?php

namespace Ojs\JournalBundle\Event\JournalPage;

final class JournalPageEvents
{
    const LISTED = 'ojs.journal_page.list';

    const PRE_CREATE = 'ojs.journal_page.pre_create';

    const POST_CREATE = 'ojs.journal_page.post_create';

    const PRE_UPDATE = 'ojs.journal_page.pre_update';

    const POST_UPDATE = 'ojs.journal_page.post_update';

    const PRE_DELETE = 'ojs.journal_page.pre_delete';

    const POST_DELETE = 'ojs.journal_page.post_delete';
}
