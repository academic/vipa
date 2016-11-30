<?php

namespace Ojs\JournalBundle\Listeners;

use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Event\Article\ArticleEvents;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\UserBundle\Entity\User;

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
        $getMailEvent = $this->ojsMailer->getEventByName(ArticleEvents::POST_CREATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'article.title'     => $itemEvent->getItem()->getTitle(),
                'done.by'           => $this->ojsMailer->currentUser()->getFullName(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePostUpdate(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(ArticleEvents::POST_UPDATE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'article.title'     => $itemEvent->getItem()->getTitle(),
                'done.by'           => $this->ojsMailer->currentUser()->getFullName(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePreDelete(JournalItemEvent $itemEvent)
    {
        $getMailEvent = $this->ojsMailer->getEventByName(ArticleEvents::PRE_DELETE, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        /** @var User $user */
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'           => (string)$itemEvent->getItem()->getJournal(),
                'article.title'     => $itemEvent->getItem()->getTitle(),
                'done.by'           => $this->ojsMailer->currentUser()->getFullName(),
                'receiver.username' => $user->getUsername(),
                'receiver.fullName' => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }
    }

    /**
     * @param JournalItemEvent $itemEvent
     */
    public function onArticlePostSubmit(JournalItemEvent $itemEvent)
    {
        /** @var Article $article */
        $article = $itemEvent->getItem();
        $submitterUser = $article->getSubmitterUser();

        $getMailEvent = $this->ojsMailer->getEventByName(ArticleEvents::POST_SUBMIT, null, $itemEvent->getItem()->getJournal());
        if(!$getMailEvent){
            return;
        }
        foreach ($this->ojsMailer->getJournalRelatedUsers() as $user) {
            $transformParams = [
                'journal'               => (string)$itemEvent->getItem()->getJournal(),
                'article.title'         => $itemEvent->getItem()->getTitle(),
                'submitter.fullName'    => $submitterUser->getFullName(),
                'receiver.username'     => $user->getUsername(),
                'receiver.fullName'     => $user->getFullName(),
            ];
            $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
            $this->ojsMailer->sendToUser(
                $user,
                $getMailEvent->getSubject(),
                $template
            );
        }

        //send mail to submitter user
        $transformParams = [
            'journal'               => (string)$itemEvent->getItem()->getJournal(),
            'article.title'         => $itemEvent->getItem()->getTitle(),
            'submitter.fullName'    => $submitterUser->getFullName(),
            'receiver.username'     => $submitterUser->getUsername(),
            'receiver.fullName'     => $submitterUser->getFullName(),
        ];
        $template = $this->ojsMailer->transformTemplate($getMailEvent->getTemplate(), $transformParams);
        $this->ojsMailer->sendToUser(
            $submitterUser,
            $getMailEvent->getSubject(),
            $template
        );
    }
}
