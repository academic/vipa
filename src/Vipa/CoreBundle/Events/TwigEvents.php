<?php

namespace Vipa\CoreBundle\Events;

class TwigEvents
{
    const VIPA_USER_PROFILE_EDIT_TABS = 'vipa.user.profile.edit.tabs';
    const VIPA_USER_PROFILE_PUBLIC_VIEW = 'vipa.user.profile.public.view';
    const VIPA_USER_PROFILE_PUBLIC_VIEW_SCRIPT = 'vipa.user.profile.public.view.script';
    const VIPA_USER_ARTICLE_ACTIONS = 'vipa.core.user.article.actions';
    const VIPA_SUBMISSION_CITATION_VIEW = 'vipa.submission.citation.view';
    const VIPA_SUBMISSION_CITATION_FORM_EXTRA = 'vipa.submission.citation.form_extra';
    const VIPA_NEW_ARTICLE_SUBMISSION_SCRIPT = 'vipa.new.article.submission.script';
    const VIPA_ISSUE_SHOW_VIEW = "vipa.journal.issue.show.view";
    const VIPA_JOURNAL_APPLICATION_EXTRA_FIELDS = "vipa.journal.application.extra.fields";
    const VIPA_JOURNAL_APPLICATION_SCRIPT = "vipa.journal.application.script";
    const VIPA_SEARCH_PAGE_WARNING = "vipa.search.page.warning";
    const VIPA_ARTICLE_SHOW_VIEW = 'vipa.article.show.view';
    const VIPA_ARTICLE_EDIT_VIEW = 'vipa.article.edit.view';
    const VIPA_ADMIN_STATS_DOI_TABS = 'vipa.admin.stats.doi.tabs';
    const VIPA_ADMIN_STATS_DOI_CONTENT = 'vipa.admin.stats.doi.content';
    const VIPA_ADMIN_STATS_DOI_SCRIPT = 'vipa.admin.stats.doi.script';
    const VIPA_ADMIN_STATS_EXTRA_TABS = 'vipa.admin.stats.extra.tabs';
    const VIPA_ADMIN_STATS_EXTRA_CONTENT = 'vipa.admin.stats.extra.content';
    const VIPA_ADMIN_STATS_EXTRA_SCRIPT = 'vipa.admin.stats.extra.script';
    const VIPA_JOURNAL_ARTICLE_EVENT_FORM = 'vipa.journal.article.event.form';
}