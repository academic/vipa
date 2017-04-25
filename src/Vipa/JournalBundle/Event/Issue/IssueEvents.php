<?php

namespace Vipa\JournalBundle\Event\Issue;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class IssueEvents implements MailEventsInterface
{
    const LISTED = 'vipa.issue.list';

    const PRE_CREATE = 'vipa.issue.pre_create';

    const POST_CREATE = 'vipa.issue.post_create';

    const PRE_UPDATE = 'vipa.issue.pre_update';

    const POST_UPDATE = 'vipa.issue.post_update';

    const PRE_DELETE = 'vipa.issue.pre_delete';

    const POST_DELETE = 'vipa.issue.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'journal', 'issue', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'journal', 'issue', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'journal', 'issue', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
