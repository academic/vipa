<?php

namespace Ojs\JournalBundle\Event\JournalPost;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalPostEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_post.list';

    const PRE_CREATE = 'ojs.journal_post.pre_create';

    const POST_CREATE = 'ojs.journal_post.post_create';

    const PRE_UPDATE = 'ojs.journal_post.pre_update';

    const POST_UPDATE = 'ojs.journal_post.post_update';

    const PRE_DELETE = 'ojs.journal_post.pre_delete';

    const POST_DELETE = 'ojs.journal_post.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail($this::POST_CREATE, 'journal', []),
            new EventDetail($this::POST_UPDATE, 'journal', []),
            new EventDetail($this::POST_DELETE, 'journal', []),
        ];
    }
}
