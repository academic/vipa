<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step3;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalSetupStep3Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 3 - Saves Journal 's step 3 data
     * @param Request $request
     * @return JsonResponse
     */
    public function addIndexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $step1Form = $this->createForm(new Step3(), $journal);
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