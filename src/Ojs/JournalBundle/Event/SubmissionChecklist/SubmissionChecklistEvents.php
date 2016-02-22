<?php

namespace Ojs\JournalBundle\Event\SubmissionChecklist;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class SubmissionChecklistEvents implements MailEventsInterface
{
    const LISTED = 'ojs.submission_checklist.list';

    const PRE_CREATE = 'ojs.submission_checklist.pre_create';

    const POST_CREATE = 'ojs.submission_checklist.post_create';

    const PRE_UPDATE = 'ojs.submission_checklist.pre_update';

    const POST_UPDATE = 'ojs.submission_checklist.post_update';

    const PRE_DELETE = 'ojs.submission_checklist.pre_delete';

    const POST_DELETE = 'ojs.submission_checklist.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'journal.user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'journal.user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_DELETE, 'journal', [
                'journal.user', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
