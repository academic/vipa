<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step6;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Journal Setup Wizard Step controller.
 *
 */
class JournalSetupStep6Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 6 - Saves Journal 's step 6 data
     * @param Request $request
     * @param $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $setupId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $setup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneById($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournalId());
        $step6Form = $this->createForm(new Step6(), $journal);
        $step6Form->handleRequest($request);
        if ($step6Form->isValid()) {
            $journal->setSetupStatus(true);
            $em->flush();
            //remove journal setup progress data
            $dm->remove($setup);
            $dm->flush();
            $journalLink = $this->get('ojs.journal_service')->generateUrl($journal);
            return new JsonResponse(array(
                'success' => '1',
                'journalLink' => $journalLink
            ));
        } else {
            return new JsonResponse(array(
                'success' => '0'));
        }
    }

    /**
     * manager current journal setup step 6
     * @param Request $request
     * @return JsonResponse
     */
    public function managerUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $step6Form = $this->createForm(new Step6(), $currentJournal);
        $step6Form->handleRequest($request);
        if ($step6Form->isValid()) {
            $currentJournal->setSetupStatus(true);
            $em->flush();
            $journalLink = $this->get('ojs.journal_service')->generateUrl($currentJournal);
            return new JsonResponse(array(
                'success' => '1',
                'journalLink' => $journalLink
            ));
        } else {
            return new JsonResponse(array(
                'success' => '0'));
        }
    }
}
