<?php

namespace Ojs\JournalBundle\Event\JournalSubmissionFile;

final class JournalSubmissionFileEvents
{
    const LISTED = 'ojs.journal_submission_file.list';

    const PRE_CREATE = 'ojs.journal_submission_file.pre_create';

    const POST_CREATE = 'ojs.journal_submission_file.post_create';

    const PRE_UPDATE = 'ojs.journal_submission_file.pre_update';

    const POST_UPDATE = 'ojs.journal_submission_file.post_update';

    const PRE_DELETE = 'ojs.journal_submission_file.pre_delete';

    const POST_DELETE = 'ojs.journal_submission_file.post_delete';

    const PRE_SUBMIT = 'ojs.journal_submission_file.pre_submit';

    const POST_SUBMIT = 'ojs.journal_submission_file.post_submit';
}
