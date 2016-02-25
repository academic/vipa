<?php

namespace Ojs\JournalBundle\Event\Design;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class DesignEvents implements MailEventsInterface
{
    const LISTED = 'ojs.design.list';

    const PRE_CREATE = 'ojs.design.pre_create';

    const POST_CREATE = 'ojs.design.post_create';

    const PRE_UPDATE = 'ojs.design.pre_update';

    const POST_UPDATE = 'ojs.design.post_update';

    const PRE_DELETE = 'ojs.design.pre_delete';

    const POST_DELETE = 'ojs.design.post_delete';

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
