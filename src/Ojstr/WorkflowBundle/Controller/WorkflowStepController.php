<?php

namespace Ojstr\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class WorkflowStepController extends Controller {

    public function indexAction() {
        $steps = $this->get('doctrine_mongodb')
                ->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')
                ->findAll();
        return $this->render(
                        'OjstrWorkflowBundle:WorkflowStep:index.html.twig', array('steps' => $steps)
        );
    }

    /**
     * render "new workflow" flow
     */
    public function newAction() {
        $session = new Session();
        $selectedJournalId = $session->get('selectedJournalId');
        if (!$selectedJournalId) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $em->getRepository('OjstrJournalBundle:Journal')->findOneById($selectedJournalId);
        $roles = $em->getRepository('OjstrUserBundle:Role')->findAll();
        $nextSteps = $this->get('doctrine_mongodb')
                ->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journal_id', $selectedJournal->getId()));
        return $this->render('OjstrWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'roles' => $roles, 'nextSteps' => $nextSteps, 'journal' => $selectedJournal));
    }

    /**
     * insert new step with data from "new workflow" form data
     */
    public function createAction() {
        return $this->redirect($this->generateUrl('manage_workflowsteps'));
    }

    public function editAction() {
        return NULL;
    }

    public function updateAction() {
        return NULL;
    }

}
