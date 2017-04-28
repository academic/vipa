<?php

namespace Vipa\JournalBundle\Event\JournalPost;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalPostEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_post.list';

    const PRE_CREATE = 'vipa.journal_post.pre_create';

    const POST_CREATE = 'vipa.journal_post.post_create';

    const PRE_UPDATE = 'vipa.journal_post.pre_update';

    const POST_UPDATE = 'vipa.journal_post.post_update';

    const PRE_DELETE = 'vipa.journal_post.pre_delete';

    const POST_DELETE = 'vipa.journal_post.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'post', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'post', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'post', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
