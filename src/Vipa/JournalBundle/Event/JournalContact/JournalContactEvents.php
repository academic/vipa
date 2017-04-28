<?php

namespace Vipa\JournalBundle\Event\JournalContact;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class JournalContactEvents implements MailEventsInterface
{
    const LISTED = 'vipa.journal_contact.list';

    const PRE_CREATE = 'vipa.journal_contact.pre_create';

    const POST_CREATE = 'vipa.journal_contact.post_create';

    const PRE_UPDATE = 'vipa.journal_contact.pre_update';

    const POST_UPDATE = 'vipa.journal_contact.post_update';

    const PRE_DELETE = 'vipa.journal_contact.pre_delete';

    const POST_DELETE = 'vipa.journal_contact.post_delete';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'contact', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'contact', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'contact', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
