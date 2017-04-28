<?php

namespace Vipa\JournalBundle\Listeners;

use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Event\Article\ArticleEvents;
use Vipa\JournalBundle\Event\JournalItemEvent;

class ArticleMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ArticleEvents::POST_CREATE => 'onArticlePostCreate',
            ArticleEvents::POST_UPDATE => 'onArticlePostUpdate',
            ArticleEvents::POST_SUBMIT => 'onArticlePostSubmit',
            ArticleEvents::PRE_DELETE  => 'onArticlePreDelete',
        ];
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onArticlePostCreate(JournalItemEvent $event)
    {
        $this->sendArticleMail($event, ArticleEvents::POST_CREATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onArticlePostUpdate(JournalItemEvent $event)
    {
        $this->sendArticleMail($event, ArticleEvents::POST_UPDATE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onArticlePreDelete(JournalItemEvent $event)
    {
        $this->sendArticleMail($event, ArticleEvents::PRE_DELETE);
    }

    /**
     * @param JournalItemEvent $event
     */
    public function onArticlePostSubmit(JournalItemEvent $event)
    {
        $article = $event->getItem();

        if (!$article instanceof Article) {
            return;
        }

        $submitterUser = $article->getSubmitterUser();
        $params = ['submitter.fullName' => $submitterUser->getFullName()];
        $this->sendArticleMail($event, ArticleEvents::POST_SUBMIT, [$submitterUser], $params);
    }

    /**
     * @param JournalItemEvent $event
     * @param string $name
     * @param array $extraUsers
     * @param array $extraParams
     */
    private function sendArticleMail(
        JournalItemEvent $event,
        string $name,
        array $extraUsers = [],
        array $extraParams = []
    ) {
        /** @var Article $article */
        $article = $event->getItem();
        $journal = $article->getJournal();
        $users = array_merge($this->mailer->getJournalStaff(), $extraUsers);
        $params = array_merge(['journal' => (string) $journal, 'article.title' => $article->getTitle()], $extraParams);
        $this->mailer->sendEventMail($name, $users, $params, $journal);
    }
}
