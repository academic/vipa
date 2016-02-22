<?php

namespace Ojs\JournalBundle\Event;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal.list';

    const PRE_CREATE = 'ojs.journal.pre_create';

    const POST_CREATE = 'ojs.journal.post_create';

    const PRE_UPDATE = 'ojs.journal.pre_update';

    const POST_UPDATE = 'ojs.journal.post_update';

    const PRE_DELETE = 'ojs.journal.pre_delete';

    const POST_DELETE = 'ojs.journal.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'admin', [
                'journal', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'admin', [
                'journal', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_DELETE, 'admin', [
                'journal', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
