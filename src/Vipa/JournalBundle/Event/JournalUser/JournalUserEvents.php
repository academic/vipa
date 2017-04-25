<?php

namespace Vipa\JournalBundle\Event\JournalUser;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalUserEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_user.list';

    const PRE_CREATE = 'vipa.journal_user.pre_create';

    const POST_CREATE = 'vipa.journal_user.post_create';

    const PRE_UPDATE = 'vipa.journal_user.pre_update';

    const POST_UPDATE = 'vipa.journal_user.post_update';

    const PRE_DELETE = 'vipa.journal_user.pre_delete';

    const POST_DELETE = 'vipa.journal_user.post_delete';

    const PRE_ADD_JOURNAL = 'vipa.journal_user.pre_add_journal';

    const POST_ADD_JOURNAL = 'vipa.journal_user.post_add_journal';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'journal', 'journal.user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'journal', 'journal,user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'journal', 'journal.user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_ADD_JOURNAL, 'journal', [
                'journal', 'journal.user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
