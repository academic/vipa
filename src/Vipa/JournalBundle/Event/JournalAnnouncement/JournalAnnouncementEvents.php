<?php

namespace Vipa\JournalBundle\Event\JournalAnnouncement;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalAnnouncementEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_announcement.list';

    const PRE_CREATE = 'vipa.journal_announcement.pre_create';

    const POST_CREATE = 'vipa.journal_announcement.post_create';

    const PRE_UPDATE = 'vipa.journal_announcement.pre_update';

    const POST_UPDATE = 'vipa.journal_announcement.post_update';

    const PRE_DELETE = 'vipa.journal_announcement.pre_delete';

    const POST_DELETE = 'vipa.journal_announcement.post_delete';

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
