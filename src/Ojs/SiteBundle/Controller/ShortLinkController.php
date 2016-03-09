<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;

class ShortLinkController extends Controller
{
    public function articleAction($identifier)
    {
        $repo = $this->getDoctrine()->getRepository('OjsJournalBundle:Article');
        $article = is_numeric($identifier) ?
            $repo->find(intval($identifier)) :
            $repo->findOneBy(['doi' => $identifier]);
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
