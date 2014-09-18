<?php

namespace Ojstr\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @param int $id
     */
    public function articleViewsAction($id = null)
    {
        if (!empty($id)) {
            $article = $this->getDoctrine()->getManager()->getRepository("OjstrJournalBundle:Article")->find($id);
            $stats = array();

            return $this->render('OjstrAnalyticsBundle:Article:views_one.html.twig', array(
                        'article' => $article,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjstrAnalyticsBundle:Article:views_all.html.twig');
    }

    /**
     * @param int $id
     */
    public function articleDownloadsAction($id = null)
    {
        if (!empty($id)) {
            $article = $this->getDoctrine()->getManager()->getRepository("OjstrJournalBundle:Article")->find($id);
            $stats = array();

            return $this->render('OjstrAnalyticsBundle:Article:downloads_one.html.twig', array(
                        'article' => $article,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjstrAnalyticsBundle:Article:downloads_all.html.twig');
    }

}
