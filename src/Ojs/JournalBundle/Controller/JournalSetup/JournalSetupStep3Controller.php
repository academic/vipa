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
     * @param null $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request ,$setupId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $setup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneById($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournalId());
        $step3Form = $this->createForm(new Step3(), $journal);
        $step3Form->handleRequest($request);
        if ($step3Form->isValid()) {
            $setup->setCurrentStep(4);
            $dm->flush();
            $em->flush();
            return new JsonResponse(array(
                'success' => '1'));
        }else{
            return new JsonResponse(array(
                'success' => '0'));
        }
    }
}