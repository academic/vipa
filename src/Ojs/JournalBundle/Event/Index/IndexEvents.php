<?php

namespace Ojs\JournalBundle\Event\Index;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class IndexEvents implements MailEventsInterface
{
    const LISTED = 'ojs.index.list';

    const PRE_CREATE = 'ojs.index.pre_create';

    const POST_CREATE = 'ojs.index.post_create';

    const PRE_UPDATE = 'ojs.index.pre_update';

    const POST_UPDATE = 'ojs.index.post_update';

    const PRE_DELETE = 'ojs.index.pre_delete';

    const POST_DELETE = 'ojs.index.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'admin', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'admin', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_DELETE, 'admin', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
