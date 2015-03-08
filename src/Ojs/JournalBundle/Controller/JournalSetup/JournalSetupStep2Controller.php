<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step2;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalSetupStep2Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 2 - Saves Journal 's step 2 data
     * @param Request $request
     * @param null $journalId
     * @return JsonResponse
     */
    public function updateAction(Request $request,$journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }
        $step2Form = $this->createForm(new Step2(), $journal);
        $step2Form->handleRequest($request);
        if ($step2Form->isValid()) {
            $em->flush();
            return new JsonResponse(array(
                'success' => '1'));
        }else{
            return new JsonResponse(array(
                'success' => '0'));
        }
    }
}