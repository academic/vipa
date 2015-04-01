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
     * @param $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request,$setupId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $setup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneById($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournalId());
        $step1Form = $this->createForm(new Step1(), $journal);
        $step1Form->handleRequest($request);
        if ($step1Form->isValid()) {
            $setup->setCurrentStep(2);
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