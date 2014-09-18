<?php

namespace Ojstr\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjstrAnalyticsBundle::index.html.twig');
    }

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

            return $this->render('OjstrAnalyticsBundle:Journal:summary_one.html.twig', array(
                        'journal' => $journal,
                        'stats' =>  $stats
            ));
        }
        // else get all journals
        return $this->render('OjstrAnalyticsBundle:Journal:summary_all.html.twig');
    }

}
