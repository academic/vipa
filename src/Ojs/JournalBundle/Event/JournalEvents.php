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
    const JOURNAL_THEME_CHANGE = 'ojs.journal.theme.change';
    const JOURNAL_DESIGN_CHANGE = 'ojs.journal.design.change';
    const JOURNAL_ARTICLE_CHANGE = 'ojs.journal.article.change';
    const JOURNAL_ARTICLE_SUBMITTED = 'ojs.journal.article.submit.happen';
    const JOURNAL_CONTACT_CHANGE = 'ojs.journal.contact.change';
    const JOURNAL_ISSUE_CHANGE = 'ojs.journal.issue.change';
    const JOURNAL_SECTION_CHANGE = 'ojs.journal.section.change';
    const JOURNAL_INDEX_CHANGE = 'ojs.journal.index.change';
    const JOURNAL_BOARD_CHANGE = 'ojs.journal.board.change';
    const JOURNAL_PERIOD_CHANGE = 'ojs.journal.period.change';
    const JOURNAL_POST_CHANGE = 'ojs.journal.post.change';
    const JOURNAL_ANNOUNCEMENT_CHANGE = 'ojs.journal.announcement.change';
    const JOURNAL_PAGE_CHANGE = 'ojs.journal.page.change';
}