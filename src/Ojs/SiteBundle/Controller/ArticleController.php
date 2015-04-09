<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\ArticleEventLog;

class ArticleController extends Controller {

    public function articlePageAction($slug, $issue_id, $article_slug)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article */
        $data['article'] = $em->getRepository('OjsJournalBundle:Article')->findOneBy(['slug' => $article_slug]);
        if (!$data['article']) {
            throw $this->createNotFoundException($this->get('translator')->trans('Article Not Found'));
        }
        //log article view event
        $data['schemaMetaTag'] = '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />';
        $data['meta'] = $this->get('ojs.article_service')->generateMetaTags($data['article']);
        $data['journal'] = $data['article']->getJournal();
        $data['page'] = 'journals';
        $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);

        return $this->render('OjsSiteBundle:Article:article_page.html.twig', $data);
    }

    /**
     * article view event log
     * @param $article
     */
    public function articleViewLog($article)
    {
        $entity = new ArticleEventLog();
        $em = $this->getDoctrine()->getManager();
        $entity->setArticleId($article->getId());
        $entity->setEventInfo(\Ojs\Common\Params\ArticleEventLogParams::$ARTICLE_VIEW);
        $entity->setIp($this->container->get('request')->getClientIp());
        $em->persist($entity);
        $em->flush();
    }
}
