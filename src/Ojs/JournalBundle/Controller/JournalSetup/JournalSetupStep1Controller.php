<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step1;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalSetupStep1Controller extends Controller
{

    /**
     * Journal Setup Wizard Step 1 - Saves Journal 's step 1 data
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
        $step1Form = $this->createForm(new Step1(), $journal);
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