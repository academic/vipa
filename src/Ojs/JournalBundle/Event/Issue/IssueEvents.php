<?php

namespace Ojs\JournalBundle\Event\Issue;

final class IssueEvents
{
    const LISTED = 'ojs.issue.list';

    const PRE_CREATE = 'ojs.issue.pre_create';

    const POST_CREATE = 'ojs.issue.post_create';

    const PRE_UPDATE = 'ojs.issue.pre_update';

    const POST_UPDATE = 'ojs.issue.post_update';

    const PRE_DELETE = 'ojs.issue.pre_delete';

    const POST_DELETE = 'ojs.issue.post_delete';
}
