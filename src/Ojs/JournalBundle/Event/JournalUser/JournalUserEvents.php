<?php

namespace Ojs\JournalBundle\Event\JournalUser;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalUserEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_user.list';

    const PRE_CREATE = 'ojs.journal_user.pre_create';

    const POST_CREATE = 'ojs.journal_user.post_create';

    const PRE_UPDATE = 'ojs.journal_user.pre_update';

    const POST_UPDATE = 'ojs.journal_user.post_update';

    const PRE_DELETE = 'ojs.journal_user.pre_delete';

    const POST_DELETE = 'ojs.journal_user.post_delete';

    const PRE_ADD_JOURNAL = 'ojs.journal_user.pre_add_journal';

    const POST_ADD_JOURNAL = 'ojs.journal_user.post_add_journal';

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
