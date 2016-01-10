<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Event\Article\ArticleEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;

class ArticleMailer extends AbstractJournalItemMailer
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ArticleEvents::POST_CREATE => 'onArticlePostCreate',
            ArticleEvents::POST_UPDATE => 'onArticlePostUpdate',
            ArticleEvents::PRE_DELETE => 'onArticlePreDelete',
            ArticleEvents::POST_SUBMIT => 'onArticlePostSubmit',
        );
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePostCreate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Article', 'Created');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePostUpdate(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Article', 'Updated');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePreDelete(JournalItemEvent $itemEvent)
    {
        $this->sendMail($itemEvent, 'Article', 'Deleted');
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePostSubmit(JournalItemEvent $itemEvent)
    {
        /** @var Article $article */
        $article = $itemEvent->getItem();
        $submitterUser = $article->getSubmitterUser();

        $this->sendMail($itemEvent, 'Article', 'Submitted');

        //send mail to author
        $this->ojsMailer->sendToUser(
            $submitterUser,
            'Journal Event : Journal Article Submitted Success',
            'Journal Event : Journal Article Submitted Success-> by '.$submitterUser->getUsername()
        );
    }
}
