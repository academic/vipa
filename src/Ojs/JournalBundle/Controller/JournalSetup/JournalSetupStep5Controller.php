<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step5;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalSetupStep5Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 5 - Saves Journal 's step 5 data
     * @param Request $request
     * @param null $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request,$setupId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $setup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneById($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournalId());
        $step5Form = $this->createForm(new Step5(), $journal);
        $step5Form->handleRequest($request);
        if ($step5Form->isValid()) {
            $setup->setCurrentStep(6);
            $dm->flush();
            $em->flush();
            return new JsonResponse(array(
                'success' => '1'));
        }else{
            return new JsonResponse(array(
                'success' => '0'));
        }
    }

    /**
     * manager current journal setup step 5
     * @param Request $request
     * @return JsonResponse
     */
    public function managerUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $step1Form = $this->createForm(new Step5(), $currentJournal);
        $step1Form->handleRequest($request);
        if ($step1Form->isValid()) {
            $em->flush();
            return new JsonResponse(array(
                'success' => '1'));
        }else{
            return new JsonResponse(array(
                'success' => '0'));
        }
    }
}