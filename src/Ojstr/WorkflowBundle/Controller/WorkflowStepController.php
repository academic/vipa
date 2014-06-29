<?php

namespace Ojstr\WorkflowBundle\Controller;

class WorkflowStepController extends \Ojstr\Common\Controller\OjsController {

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
        $session = new \Symfony\Component\HttpFoundation\Session\Session();
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
        $repo = $this->get('doctrine_mongodb')
                ->getRepository('OjstrWorkflowBundle:JournalWorkflowStep');
        $step = new \Ojstr\WorkflowBundle\Document\JournalWorkflowStep();
        $step->setDeadline($deadline);
        return $this->redirect($this->generateUrl('manage_workflowsteps'));
    }

    public function editAction() {
        return NULL;
    }

    public function updateAction() {
        return NULL;
    }

}
