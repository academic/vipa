<?php

namespace Ojs\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @param int $id
     */
    public function articleViewsAction($id = null)
    {
        if (!empty($id)) {
            $article = $this->getDoctrine()->getManager()->getRepository("OjsJournalBundle:Article")->find($id);
            $stats = array();

            return $this->render('OjsAnalyticsBundle:Article:views_one.html.twig', array(
                        'article' => $article,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjsAnalyticsBundle:Article:views_all.html.twig');
    }

    /**
     * @param int $id
     */
    public function articleDownloadsAction($id = null)
    {
        if (!empty($id)) {
            $article = $this->getDoctrine()->getManager()->getRepository("OjsJournalBundle:Article")->find($id);
            $stats = array();

            return $this->render('OjsAnalyticsBundle:Article:downloads_one.html.twig', array(
                        'article' => $article,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjsAnalyticsBundle:Article:downloads_all.html.twig');
    }

}
