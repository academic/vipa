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
     * @param null $journalId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }
        $step6Form = $this->createForm(new Step6(), $journal);
        $step6Form->handleRequest($request);
        $journalLink = $this->generateUrl('ojs_journal_index', array(
            'slug' => $journal->getSlug(),
            'institution' => $journal->getInstitution()->getSlug()
        ));
        if ($step6Form->isValid()) {
            $journal->setSetupStatus(true);
            $em->flush();
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
