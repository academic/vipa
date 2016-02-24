<?php

namespace Ojs\JournalBundle\Event\Article;

use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;

final class ArticleEvents implements MailEventsInterface
{
    const LISTED = 'ojs.article.list';

    const PRE_CREATE = 'ojs.article.pre_create';

    const POST_CREATE = 'ojs.article.post_create';

    const PRE_UPDATE = 'ojs.article.pre_update';

    const POST_UPDATE = 'ojs.article.post_update';

    const PRE_DELETE = 'ojs.article.pre_delete';

    const POST_DELETE = 'ojs.article.post_delete';

    const PRE_SUBMIT = 'ojs.article.pre_submit';

    const POST_SUBMIT = 'ojs.article.post_submit';

    const INIT_SUBMIT_FORM = 'ojs.article.init_submit_form';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'article.title', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'article.title', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'article.title', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_SUBMIT, 'journal', [
                'article.title', 'submitter.username', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
