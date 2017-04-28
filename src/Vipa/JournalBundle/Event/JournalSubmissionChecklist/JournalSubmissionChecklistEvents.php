<?php

namespace Vipa\JournalBundle\Event\JournalSubmissionChecklist;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalSubmissionChecklistEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_submission_checklist.list';

    const PRE_CREATE = 'vipa.journal_submission_checklist.pre_create';

    const POST_CREATE = 'vipa.journal_submission_checklist.post_create';

    const PRE_UPDATE = 'vipa.journal_submission_checklist.pre_update';

    const POST_UPDATE = 'vipa.journal_submission_checklist.post_update';

    const PRE_DELETE = 'vipa.journal_submission_checklist.pre_delete';

    const POST_DELETE = 'vipa.journal_submission_checklist.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'submission.checklist', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'submission.checklist', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'submission.checklist', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
