<?php

namespace Ojs\JournalBundle\Event\JournalSubmissionChecklist;

final class JournalSubmissionChecklistEvents
{
    const LISTED = 'ojs.journal_submission_checklist.list';

    const PRE_CREATE = 'ojs.journal_submission_checklist.pre_create';

    const POST_CREATE = 'ojs.journal_submission_checklist.post_create';

    const PRE_UPDATE = 'ojs.journal_submission_checklist.pre_update';

    const POST_UPDATE = 'ojs.journal_submission_checklist.post_update';

    const PRE_DELETE = 'ojs.journal_submission_checklist.pre_delete';

    const POST_DELETE = 'ojs.journal_submission_checklist.post_delete';
}
