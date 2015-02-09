<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;


class ArticleController extends Controller
{


    public function articlePageAction($slug, $article_slug)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article */
        $data['article'] = $em->getRepository('OjsJournalBundle:Article')->findOneBy(['slug' => $article_slug]);
        if (!$data['article'])
            throw $this->createNotFoundException($this->get('translator')->trans('Article Not Found'));

        $data['journal'] = $data['article']->getJournal();
        $data['page'] = 'journals';
        $data['blocks'] = $em->getRepository('OjsSiteBundle:Block')->journalBlocks($data['journal']);


        return $this->render('OjsSiteBundle:Article:article_page.html.twig', $data);
    }
}