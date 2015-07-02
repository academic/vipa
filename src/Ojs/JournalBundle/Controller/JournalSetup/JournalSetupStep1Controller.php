<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalSetupProgress;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step1;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class JournalSetupStep1Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 1 - Saves Journal 's step 1 data
     * @param  Request      $request
     * @param $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $setupId = null)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step1Form = $this->createForm(new Step1(), $journal);
        $step1Form->handleRequest($request);
        if ($step1Form->isValid()) {
            $setup->setCurrentStep(2);
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
