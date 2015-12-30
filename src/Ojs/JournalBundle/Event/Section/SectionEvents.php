<?php

namespace Ojs\JournalBundle\Event\Section;

final class SectionEvents
{
    const LISTED = 'ojs.section.list';

    const PRE_CREATE = 'ojs.section.pre_create';

    const POST_CREATE = 'ojs.section.post_create';

    const PRE_UPDATE = 'ojs.section.pre_update';

    const POST_UPDATE = 'ojs.section.post_update';

    const PRE_DELETE = 'ojs.section.pre_delete';

    const POST_DELETE = 'ojs.section.post_delete';
}
