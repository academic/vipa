<?php

namespace Ojs\AnalyticsBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @param  null|int $id
     * @return Response
     */
    public function articleViewsAction($id = null)
    {
        if (!empty($id)) {
            $article = $this->getDoctrine()->getManager()->getRepository("OjsJournalBundle:Article")->find($id);
            $stats = array();

            return $this->render(
                'OjsAnalyticsBundle:Article:views_one.html.twig',
                array(
                    'article' => $article,
                    'stats' => $stats,
                )
            );
        }

        // else get all journals
        return $this->render('OjsAnalyticsBundle:Article:views_all.html.twig');
    }

    /**
     * @param  null|int $id
     * @return Response
     */
    public function articleDownloadsAction($id = null)
    {
        if (!empty($id)) {
            $article = $this->getDoctrine()->getManager()->getRepository("OjsJournalBundle:Article")->find($id);
            $stats = array();

            return $this->render(
                'OjsAnalyticsBundle:Article:downloads_one.html.twig',
                array(
                    'article' => $article,
                    'stats' => $stats,
                )
            );
        }

        // else get all journals
        return $this->render('OjsAnalyticsBundle:Article:downloads_all.html.twig');
    }
}
