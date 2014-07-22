<?php

namespace Ojstr\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JournalController extends Controller {

    /**
     * Get a journal analytics summary
     * If there is no $id given, list all journals and analytics summary data
     * @param int $id 
     */
    public function journalSummaryAction($id = NULL) {
        if (!empty($id)) {
            $journal = $this->getDoctrine()->getManager()->getRepository("OjstrJournalBundle:Journal")->find($id);
            $stats = array();
            return $this->render('OjstrAnalyticsBundle:Journal:summary_one.html.twig', array(
                        'journal' => $journal,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjstrAnalyticsBundle:Journal:summary_all.html.twig');
    }
    
    public function journalViewsAction($id = NULL) {
        if (!empty($id)) {
            $journal = $this->getDoctrine()->getManager()->getRepository("OjstrJournalBundle:Journal")->find($id);
            $stats = array();
            return $this->render('OjstrAnalyticsBundle:Journal:views_one.html.twig', array(
                        'journal' => $journal,
                        'stats' => $stats
            ));
        }
        // else get all journals
        return $this->render('OjstrAnalyticsBundle:Journal:views_all.html.twig');
    }

}
