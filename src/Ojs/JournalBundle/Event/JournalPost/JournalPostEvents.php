<?php

namespace Ojs\JournalBundle\Event\JournalPost;

final class JournalPostEvents
{
    const LISTED = 'ojs.journal_post.list';

    const PRE_CREATE = 'ojs.journal_post.pre_create';

    const POST_CREATE = 'ojs.journal_post.post_create';

    const PRE_UPDATE = 'ojs.journal_post.pre_update';

    const POST_UPDATE = 'ojs.journal_post.post_update';

    const PRE_DELETE = 'ojs.journal_post.pre_delete';

    const POST_DELETE = 'ojs.journal_post.post_delete';

    const PRE_SUBMIT = 'ojs.journal_post.pre_submit';

    const POST_SUBMIT = 'ojs.journal_post.post_submit';
}
