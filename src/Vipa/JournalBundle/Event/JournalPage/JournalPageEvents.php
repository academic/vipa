<?php

namespace Vipa\JournalBundle\Event\JournalPage;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalPageEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_page.list';

    const PRE_CREATE = 'vipa.journal_page.pre_create';

    const POST_CREATE = 'vipa.journal_page.post_create';

    const PRE_UPDATE = 'vipa.journal_page.pre_update';

    const POST_UPDATE = 'vipa.journal_page.post_update';

    const PRE_DELETE = 'vipa.journal_page.pre_delete';

    const POST_DELETE = 'vipa.journal_page.post_delete';

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
