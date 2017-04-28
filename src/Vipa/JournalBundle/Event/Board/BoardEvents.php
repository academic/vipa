<?php

namespace Vipa\JournalBundle\Event\Board;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class BoardEvents implements MailEventsInterface
{
    const LISTED = 'vipa.board.list';

    const PRE_CREATE = 'vipa.board.pre_create';

    const POST_CREATE = 'vipa.board.post_create';

    const PRE_UPDATE = 'vipa.board.pre_update';

    const POST_UPDATE = 'vipa.board.post_update';

    const PRE_DELETE = 'vipa.board.pre_delete';

    const POST_DELETE = 'vipa.board.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'journal', 'board', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'journal', 'board', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'journal', 'board', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
