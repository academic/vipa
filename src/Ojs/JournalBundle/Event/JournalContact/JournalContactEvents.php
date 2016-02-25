<?php

namespace Ojs\JournalBundle\Event\JournalContact;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class JournalContactEvents implements MailEventsInterface
{
    const LISTED = 'ojs.journal_contact.list';

    const PRE_CREATE = 'ojs.journal_contact.pre_create';

    const POST_CREATE = 'ojs.journal_contact.post_create';

    const PRE_UPDATE = 'ojs.journal_contact.pre_update';

    const POST_UPDATE = 'ojs.journal_contact.post_update';

    const PRE_DELETE = 'ojs.journal_contact.pre_delete';

    const POST_DELETE = 'ojs.journal_contact.post_delete';

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
