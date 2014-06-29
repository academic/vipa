<?php

namespace Ojstr\WorkflowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('OjstrUserBundle:Role')->findAll();
        $nextSteps = $this->get('doctrine_mongodb')
                ->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journal_id', 10));
        return $this->render(
                        'OjstrWorkflowBundle:WorkflowStep:new.html.twig', array('roles' => $roles, 'nextSteps' => $nextSteps)
        );
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
