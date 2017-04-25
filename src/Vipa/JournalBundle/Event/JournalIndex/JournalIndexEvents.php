<?php

namespace Vipa\JournalBundle\Event\JournalIndex;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalIndexEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_index.list';

    const PRE_CREATE = 'vipa.journal_index.pre_create';

    const POST_CREATE = 'vipa.journal_index.post_create';

    const PRE_UPDATE = 'vipa.journal_index.pre_update';

    const POST_UPDATE = 'vipa.journal_index.post_update';

    const PRE_DELETE = 'vipa.journal_index.pre_delete';

    const POST_DELETE = 'vipa.journal_index.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE.'.to.users', 'journal', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName', 'journal',
            ]),
            new EventDetail(self::POST_CREATE.'.to.admins', 'admin', [
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
