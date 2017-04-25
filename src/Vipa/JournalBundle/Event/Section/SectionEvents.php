<?php

namespace Vipa\JournalBundle\Event\Section;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class SectionEvents implements MailEventsInterface
{
    const LISTED = 'vipa.section.list';

    const PRE_CREATE = 'vipa.section.pre_create';

    const POST_CREATE = 'vipa.section.post_create';

    const PRE_UPDATE = 'vipa.section.pre_update';

    const POST_UPDATE = 'vipa.section.post_update';

    const PRE_DELETE = 'vipa.section.pre_delete';

    const POST_DELETE = 'vipa.section.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'section', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'section', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'section', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
