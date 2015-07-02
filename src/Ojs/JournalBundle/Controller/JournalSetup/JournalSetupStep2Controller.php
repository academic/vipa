<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalSetupProgress;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step2;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Entity\Journal;

class JournalSetupStep2Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 2 - Saves Journal 's step 2 data
     * @param  Request      $request
     * @param  null         $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());
        $step2Form = $this->createForm(new Step2(), $journal);
        $step2Form->handleRequest($request);
        if ($step2Form->isValid()) {
            $setup->setCurrentStep(3);
            $em->flush();

            return new JsonResponse(
                array(
                    'success' => '1',
                )
            );
        } else {
            return new JsonResponse(
                array(
                    'success' => '0',
                )
            );
        }
    }

    /**
     * manager current journal setup step 2
     * @param  Request      $request
     * @return JsonResponse
     */
    public function managerUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $step2Form = $this->createForm(new Step2(), $currentJournal);
        $step2Form->handleRequest($request);
        if ($step2Form->isValid()) {
            $em->flush();

            return new JsonResponse(
                array(
                    'success' => '1',
                )
            );
        } else {
            return new JsonResponse(
                array(
                    'success' => '0',
                )
            );
        }
    }
}
