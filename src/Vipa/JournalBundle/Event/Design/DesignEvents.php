<?php

namespace Vipa\JournalBundle\Event\Design;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class DesignEvents implements MailEventsInterface
{
    const LISTED = 'vipa.design.list';

    const PRE_CREATE = 'vipa.design.pre_create';

    const POST_CREATE = 'vipa.design.post_create';

    const PRE_UPDATE = 'vipa.design.pre_update';

    const POST_UPDATE = 'vipa.design.post_update';

    const PRE_DELETE = 'vipa.design.pre_delete';

    const POST_DELETE = 'vipa.design.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'design', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'design', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'design', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
