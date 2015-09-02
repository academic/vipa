<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;

class ArticleController extends Controller
{
    /**
     * @param $slug
     * @param $article_id
     * @param null $issue_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlePageAction($slug, $article_id, $issue_id = null)
    {
        $journalService = $this->get('ojs.journal_service');
        $em = $this->getDoctrine()->getManager();
        $data['article'] = $em->getRepository('OjsJournalBundle:Article')->find($article_id);
        $this->throw404IfNotFound($data['article']);
        //log article view event
        $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
        $data['meta'] = $this->get('ojs.article_service')->generateMetaTags($data['article']);
        $data['journal'] = $data['article']->getJournal();
        $data['page'] = 'journals';
        $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);
        $data['journal']->setPublicURI($journalService->generateUrl($data['journal']));
        $data['archive_uri'] = $this->generateUrl('ojs_archive_index',[
            'slug' => $data['journal']->getSlug(),
            'publisher' => $data['journal']->getPublisher()->getSlug(),
        ], true);
        $data['token'] = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('article_view');


        return $this->render('OjsSiteBundle:Article:article_page.html.twig', $data);
    }
}
