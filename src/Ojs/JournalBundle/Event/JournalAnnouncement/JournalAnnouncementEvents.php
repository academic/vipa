<?php

namespace Ojs\JournalBundle\Event\JournalAnnouncement;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalAnnouncementEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_announcement.list';

    const PRE_CREATE = 'ojs.journal_announcement.pre_create';

    const POST_CREATE = 'ojs.journal_announcement.post_create';

    const PRE_UPDATE = 'ojs.journal_announcement.pre_update';

    const POST_UPDATE = 'ojs.journal_announcement.post_update';

    const PRE_DELETE = 'ojs.journal_announcement.pre_delete';

    const POST_DELETE = 'ojs.journal_announcement.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'journal', 'announcement', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'journal', 'announcement', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'journal', 'announcement', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
