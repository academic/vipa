<?php

namespace Ojs\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JournalController extends Controller
{
    /**
     * Get a journal analytics summary
     * If there is no $id given, list all journals and analytics summary data
     * @param int $id
     */
    public function journalSummaryAction($id = null)
    {
        if (!empty($id)) {
            $journal = $this->getDoctrine()->getManager()->getRepository("OjstrJournalBundle:Journal")->find($id);
            $stats = array();

            return $this->render('OjsAnalyticsBundle:Journal:summary_one.html.twig', array(
                        'journal' => $journal,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjsAnalyticsBundle:Journal:summary_all.html.twig');
    }

    public function journalViewsAction($id = null)
    {
        if (!empty($id)) {
            $journal = $this->getDoctrine()->getManager()->getRepository("OjstrJournalBundle:Journal")->find($id);
            $stats = array();

            return $this->render('OjsAnalyticsBundle:Journal:views_one.html.twig', array(
                        'journal' => $journal,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjsAnalyticsBundle:Journal:views_all.html.twig');
    }

}
