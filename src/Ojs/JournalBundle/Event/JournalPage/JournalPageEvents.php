<?php

namespace Ojs\JournalBundle\Event\JournalPage;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalPageEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_page.list';

    const PRE_CREATE = 'ojs.journal_page.pre_create';

    const POST_CREATE = 'ojs.journal_page.post_create';

    const PRE_UPDATE = 'ojs.journal_page.pre_update';

    const POST_UPDATE = 'ojs.journal_page.post_update';

    const PRE_DELETE = 'ojs.journal_page.pre_delete';

    const POST_DELETE = 'ojs.journal_page.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'page', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'page', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'page', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
