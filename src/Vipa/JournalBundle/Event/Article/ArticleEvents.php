<?php

namespace Vipa\JournalBundle\Event\Article;

use Vipa\CoreBundle\Events\EventDetail;
use Vipa\CoreBundle\Events\MailEventsInterface;

final class ArticleEvents implements MailEventsInterface
{
    const LISTED = 'vipa.article.list';

    const PRE_CREATE = 'vipa.article.pre_create';

    const POST_CREATE = 'vipa.article.post_create';

    const PRE_UPDATE = 'vipa.article.pre_update';

    const POST_UPDATE = 'vipa.article.post_update';

    const PRE_DELETE = 'vipa.article.pre_delete';

    const POST_DELETE = 'vipa.article.post_delete';

    const PRE_SUBMIT = 'vipa.article.pre_submit';

    const POST_SUBMIT = 'vipa.article.post_submit';

    const INIT_SUBMIT_FORM = 'vipa.article.init_submit_form';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::POST_CREATE, 'journal', [
                'journal', 'article.title', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_UPDATE, 'journal', [
                'journal', 'article.title', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::PRE_DELETE, 'journal', [
                'journal', 'article.title', 'done.by', 'receiver.username', 'receiver.fullName',
            ]),
            new EventDetail(self::POST_SUBMIT, 'journal', [
                'journal', 'article.title', 'submitter.fullName', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
