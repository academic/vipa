<?php

namespace Ojs\JournalBundle\Event\JournalAnnouncement;

final class JournalAnnouncementEvents
{
    const LISTED = 'ojs.journal_announcement.list';

    const PRE_CREATE = 'ojs.journal_announcement.pre_create';

    const POST_CREATE = 'ojs.journal_announcement.post_create';

    const PRE_UPDATE = 'ojs.journal_announcement.pre_update';

    const POST_UPDATE = 'ojs.journal_announcement.post_update';

    const PRE_DELETE = 'ojs.journal_announcement.pre_delete';

    const POST_DELETE = 'ojs.journal_announcement.post_delete';
}
