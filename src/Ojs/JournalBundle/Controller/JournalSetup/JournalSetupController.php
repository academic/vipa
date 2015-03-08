<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\JournalSetup\Step1;
use Ojs\JournalBundle\Form\JournalSetup\Step2;
use Ojs\JournalBundle\Form\JournalSetup\Step3;
use Ojs\JournalBundle\Form\JournalSetup\Step4;
use Ojs\JournalBundle\Form\JournalSetup\Step5;
use Ojs\JournalBundle\Form\JournalSetup\Step6;

/**
 * Journal Setup Wizard controller.
 */
class JournalSetupController extends Controller
{
    /**
     *
     */
    public function indexAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();

        $stepsForms['step1'] = $this->createForm(new Step1(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'POST',
        ))->createView();
        $stepsForms['step2'] = $this->createForm(new Step2(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'POST',
        ))->createView();
        $stepsForms['step3'] = $this->createForm(new Step3(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'POST',
        ))->createView();
        $stepsForms['step4'] = $this->createForm(new Step4(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'POST',
        ))->createView();
        $stepsForms['step5'] = $this->createForm(new Step5(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'POST',
        ))->createView();
        $stepsForms['step6'] = $this->createForm(new Step6(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'POST',
        ))->createView();

        return $this->render('OjsJournalBundle:JournalSetup:index.html.twig', array(
            'entity' => $journal,
            'journal' => $journal,
            'steps' => $stepsForms,
            'submissionData' => NULL,
            'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    /**
     *
     */
    public function resumeAction()
    {

    }

    /**
     *
     */
    public function previewAction()
    {

    }

    /**
     *
     */
    public function finishction()
    {

    }
}