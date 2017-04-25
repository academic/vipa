<?php

namespace Vipa\SiteBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Article;

class ShortLinkController extends Controller
{
    public function articleIdAction(Article $article)
    {
        return $this->redirectToRoute(
            'vipa_article_page',
            [
                'slug' => $article->getJournal()->getSlug(),
                'article_id' => $article->getId(),
                'issue_id'   => $article->getIssue()->getId()
            ]
        );
    }

    public function articleDoiAction($doi)
    {
        $repo = $this->getDoctrine()->getRepository('VipaJournalBundle:Article');
        $articles = $repo->findByDoi($doi);
        $article = null;

        if (count($articles) > 0) {
            $article = $articles[0];
        }

        $this->throw404IfNotFound($article);
        $this->throw404IfNotFound($article->getIssue());

        return $this->redirectToRoute(
            'vipa_article_page',
            [
                'slug' => $article->getJournal()->getSlug(),
                'article_id' => $article->getId(),
                'issue_id'   => $article->getIssue()->getId()
            ]
        );
    }
}
