<?php

namespace Ojs\JournalBundle\Event\JournalContact;

final class JournalContactEvents
{
    const LISTED = 'ojs.journal_contact.list';

    const PRE_CREATE = 'ojs.journal_contact.pre_create';

    const POST_CREATE = 'ojs.journal_contact.post_create';

    const PRE_UPDATE = 'ojs.journal_contact.pre_update';

    const POST_UPDATE = 'ojs.journal_contact.post_update';

    const PRE_DELETE = 'ojs.journal_contact.pre_delete';

    const POST_DELETE = 'ojs.journal_contact.post_delete';
}
