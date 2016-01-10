<?php

namespace Ojs\JournalBundle\Event;

final class JournalEvents
{
    const JOURNAL_CHANGE = 'ojs.journal.change';
    const JOURNAL_USER_NEW = 'ojs.journal.user.new.happen';
    const JOURNAL_USER_ROLE_CHANGE = 'ojs.journal.user.role.change';
    const JOURNAL_SUBMISSION_CHECKLIST_CHANGE = 'ojs.journal.submission_checklist.change';
    const JOURNAL_SUBMISSION_FILES_CHANGE = 'ojs.journal.submission_files.change';
    const JOURNAL_SUBMISSION_FORM = 'ojs.journal.submission_form';
    const JOURNAL_SUBMISSION_RAW_CITATION = 'ojs.journal.submission.raw_citation';
    const JOURNAL_THEME_CHANGE = 'ojs.journal.theme.change';
    const JOURNAL_DESIGN_CHANGE = 'ojs.journal.design.change';
}
