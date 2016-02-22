<?php

namespace Ojs\JournalBundle\Event\Issue;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class IssueEvents implements MailEventsInterface
{
    const LISTED = 'ojs.issue.list';

    const PRE_CREATE = 'ojs.issue.pre_create';

    const POST_CREATE = 'ojs.issue.post_create';

    const PRE_UPDATE = 'ojs.issue.pre_update';

    const POST_UPDATE = 'ojs.issue.post_update';

    const PRE_DELETE = 'ojs.issue.pre_delete';

    const POST_DELETE = 'ojs.issue.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'issue', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'issue', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_DELETE, 'journal', [
                'issue', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
