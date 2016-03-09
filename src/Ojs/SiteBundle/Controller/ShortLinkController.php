<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;

class ShortLinkController extends Controller
{
    public function articleIdAction(Article $article)
    {
        return $this->redirectToRoute(
            'ojs_article_page',
            [
                'publisher' => $article->getJournal()->getPublisher()->getSlug(),
                'slug' => $article->getJournal()->getSlug(),
                'article_id' => $article->getId(),
                'issue_id'   => $article->getIssue()->getId()
            ]
        );
    }

    public function articleDoiAction($doi)
    {
        $repo = $this->getDoctrine()->getRepository('OjsJournalBundle:Article');
        $article = $repo->findOneBy(['doi' => $doi]);

        $this->throw404IfNotFound($article);
        $this->throw404IfNotFound($article->getIssue());

        return $this->redirectToRoute(
            'ojs_article_page',
            [
                'publisher' => $article->getJournal()->getPublisher()->getSlug(),
                'slug' => $article->getJournal()->getSlug(),
                'article_id' => $article->getId(),
                'issue_id'   => $article->getIssue()->getId()
            ]
        );
    }
}
