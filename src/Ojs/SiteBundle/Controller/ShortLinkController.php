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
                'slug' => $article->getJournal()->getSlug(),
                'article_id' => $article->getId(),
                'issue_id'   => $article->getIssue()->getId()
            ]
        );
    }

    public function articleDoiAction($doi)
    {
        $repo = $this->getDoctrine()->getRepository('OjsJournalBundle:Article');
        $articles = $repo->findByDoi($doi);
        $article = null;

        if (count($articles) > 0) {
            $article = $articles[0];
        }

        $this->throw404IfNotFound($article);
        $this->throw404IfNotFound($article->getIssue());

        return $this->redirectToRoute(
            'ojs_article_page',
            [
                'slug' => $article->getJournal()->getSlug(),
                'article_id' => $article->getId(),
                'issue_id'   => $article->getIssue()->getId()
            ]
        );
    }
}
