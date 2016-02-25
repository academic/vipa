<?php

namespace Ojs\JournalBundle\Event\Section;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class SectionEvents implements MailEventsInterface
{
    const LISTED = 'ojs.section.list';

    const PRE_CREATE = 'ojs.section.pre_create';

    const POST_CREATE = 'ojs.section.post_create';

    const PRE_UPDATE = 'ojs.section.pre_update';

    const POST_UPDATE = 'ojs.section.post_update';

    const PRE_DELETE = 'ojs.section.pre_delete';

    const POST_DELETE = 'ojs.section.post_delete';

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
