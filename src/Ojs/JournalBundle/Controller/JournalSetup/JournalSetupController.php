<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;

/**
 * Journal Setup Wizard controller.
 */
class JournalSetupController extends Controller
{
    /**
     * @param null $journalId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($journalId = null)
    {
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $em = $this->getDoctrine()->getManager();
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }
        //for 6 step create update forms
        foreach (range(1, 6) as $stepValue) {
            $stepsForms['step' . $stepValue] = $this->createFormView($journal, $stepValue);
        }
        return $this->render('OjsJournalBundle:JournalSetup:index.html.twig', array(
            'journal' => $journal,
            'steps' => $stepsForms,
        ));
    }

    /**
     * @param $journal
     * @param $stepCount
     * @return \Symfony\Component\Form\FormView
     */
    public function createFormView($journal, $stepCount)
    {
        $stepClassName  = 'Ojs\JournalBundle\Form\JournalSetup\Step'.$stepCount;
        return $this->createForm(new $stepClassName(), $journal, array(
            'method' => 'POST',
        ))->createView();
    }
}