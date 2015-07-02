<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalSetupProgress;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step5;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class JournalSetupStep5Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 5 - Saves Journal 's step 5 data
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
        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step5Form = $this->createForm(new Step5(), $journal);
        $step5Form->handleRequest($request);
        if ($step5Form->isValid()) {
            $setup->setCurrentStep(6);
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
