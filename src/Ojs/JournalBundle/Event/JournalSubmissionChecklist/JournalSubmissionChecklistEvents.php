<?php

namespace Ojs\JournalBundle\Event\JournalSubmissionChecklist;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalSubmissionChecklistEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_submission_checklist.list';

    const PRE_CREATE = 'ojs.journal_submission_checklist.pre_create';

    const POST_CREATE = 'ojs.journal_submission_checklist.post_create';

    const PRE_UPDATE = 'ojs.journal_submission_checklist.pre_update';

    const POST_UPDATE = 'ojs.journal_submission_checklist.post_update';

    const PRE_DELETE = 'ojs.journal_submission_checklist.pre_delete';

    const POST_DELETE = 'ojs.journal_submission_checklist.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail($this::POST_CREATE, 'journal', []),
            new EventDetail($this::POST_UPDATE, 'journal', []),
            new EventDetail($this::POST_DELETE, 'journal', []),
        ];
    }
}
