<?php

namespace Ojs\CoreBundle\Events;

class TwigEvents
{
    const OJS_USER_PROFILE_EDIT_TABS = 'ojs.user.profile.edit.tabs';
    const OJS_USER_PROFILE_PUBLIC_VIEW = 'ojs.user.profile.public.view';
    const OJS_USER_PROFILE_PUBLIC_VIEW_SCRIPT = 'ojs.user.profile.public.view.script';
    const OJS_USER_ARTICLE_ACTIONS = 'ojs.core.user.article.actions';
    const OJS_SUBMISSION_CITATION_VIEW = 'ojs.submission.citation.view';
    const OJS_SUBMISSION_CITATION_FORM_EXTRA = 'ojs.submission.citation.form_extra';
    const OJS_NEW_ARTICLE_SUBMISSIN_SCRIPT = 'ojs.new.article.submission.script';
    const OJS_ISSUE_SHOW_VIEW = "ojs.journal.issue.show.view";
    const OJS_JOURNAL_APPLICATION_EXTRA_FIELDS = "ojs.journal.application.extra.fields";
    const OJS_JOURNAL_APPLICATION_SCRIPT = "ojs.journal.application.script";
    const OJS_SEARCH_PAGE_WARNING = "ojs.search.page.warning";
    const OJS_ARTICLE_SHOW_VIEW = 'ojs.article.show.view';
    const OJS_ARTICLE_EDIT_VIEW = 'ojs.article.edit.view';
}