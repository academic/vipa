<?php

namespace Ojs\AnalyticsBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Response;

class JournalController extends Controller
{
    /**
     * Get a journal analytics summary
     * If there is no $id given, list all journals and analytics summary data
     *
     * @param  null|int $id
     * @return Response
     */
    public function journalSummaryAction($id = null)
    {
        if (!empty($id)) {
            $journal = $this->getDoctrine()->getManager()->getRepository("OjsJournalBundle:Journal")->find($id);
            $stats = array();

            return $this->render('OjsAnalyticsBundle:Journal:summary_one.html.twig', array(
                        'journal' => $journal,
                        'stats' => $stats,
            ));
        }
        // else get all journals
        return $this->render('OjsAnalyticsBundle:Journal:summary_all.html.twig');
    }

    /**
     * @param  null     $id
     * @return Response
     */
    public function journalViewsAction($id = null)
    {
        if (!empty($id)) {
            $journal = $this->getDoctrine()->getManager()->getRepository("OjsJournalBundle:Journal")->find($id);
            $stats = array();

            return $this->render('OjsAnalyticsBundle:Journal:views_one.html.twig', array(
                        'journal' => $journal,
                        'stats' => $stats,
            ));
        }
        // else get all journals
        return $this->render('OjsAnalyticsBundle:Journal:views_all.html.twig');
    }
}
