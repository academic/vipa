<?php

namespace Vipa\JournalBundle\Event\Index;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class IndexEvents implements MailEventsInterface
{
    const LISTED = 'vipa.index.list';

    const PRE_CREATE = 'vipa.index.pre_create';

    const POST_CREATE = 'vipa.index.post_create';

    const PRE_UPDATE = 'vipa.index.pre_update';

    const POST_UPDATE = 'vipa.index.post_update';

    const PRE_DELETE = 'vipa.index.pre_delete';

    const POST_DELETE = 'vipa.index.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'admin', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'admin', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'admin', [
                'index', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
