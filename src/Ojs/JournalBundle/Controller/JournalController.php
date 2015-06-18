<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Journal controller.
 */
class JournalController extends Controller
{
    /**
     * @param  Request          $request
     * @param $journal_id
     * @return RedirectResponse
     */
    public function changeSelectedAction(Request $request, $journal_id)
    {
        $em = $this->getDoctrine()->getManager();
        $route = $this->get('router')->generate('dashboard');
        if ($request->query->get('submission', false) === '1') {
            $route = $this->get('router')->generate('article_submission_new');
        }
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journal_id);
        $this->throw404IfNotFound($journal);
        $this->get('ojs.journal_service')->setSelectedJournal($journal);

        return $this->redirect($route);
    }

    /**
     * @return Response
     */
    public function applyAction()
    {
        return $this->render('OjsJournalBundle:Journal:apply.html.twig', array());
    }
}
