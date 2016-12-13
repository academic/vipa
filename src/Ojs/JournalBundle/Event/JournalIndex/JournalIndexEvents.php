<?php

namespace Ojs\JournalBundle\Event\JournalIndex;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalIndexEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_index.list';

    const PRE_CREATE = 'ojs.journal_index.pre_create';

    const POST_CREATE = 'ojs.journal_index.post_create';

    const PRE_UPDATE = 'ojs.journal_index.pre_update';

    const POST_UPDATE = 'ojs.journal_index.post_update';

    const PRE_DELETE = 'ojs.journal_index.pre_delete';

    const POST_DELETE = 'ojs.journal_index.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE.'.to.users', 'journal', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName', 'journal',
            ]),
            new EventDetail(self::POST_CREATE.'.to.admins', 'journal', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName', 'journal', 'journal.edit',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
