<?php

namespace Ojstr\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

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
        $dm = $this->get('doctrine_mongodb')->getManager();
        if (!$selectedJournalId) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $em->getRepository('OjstrJournalBundle:Journal')->findOneById($selectedJournalId);
        $roles = $em->getRepository('OjstrUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')
                ->findByJournalid($selectedJournalId);
        return $this->render('OjstrWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'roles' => $roles, 'nextSteps' => $nextSteps, 'journal' => $selectedJournal));
    }

    /**
     * insert new step with data from "new workflow" form data
     */
    public function createAction(Request $request) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = new \Ojstr\WorkflowBundle\Document\JournalWorkflowStep();
        $step->setMaxdays($request->get('maxdays'));
        $step->setFirststep($request->get('firststep'));
        $step->setLaststep($request->get('laststep'));
        $step->setJournalid($request->get('journalId'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        $step->setNextsteps($this->prepareNextsteps($request->get('nextsteps')));
        $step->setStatus($request->get('status'));
        $step->setTitle($request->get('title'));
        $dm->persist($step);
        $dm->flush();
        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $step->getId())));
    }

    /**
     * prepare given form values for JournalWorkflow $roles atrribute
     * @param array $nextSteps
     * @return array
     */
    protected function prepareRoles($roles) {
        $serializer = $this->container->get('serializer');
        $em = $this->getDoctrine()->getManager();
        $rolesArray = array();
        if ($roles) {
            foreach ($roles as $roleId) {
                $rolesArray[] = json_decode($serializer->serialize($em->getRepository("OjstrUserBundle:Role")->findOneById($roleId), 'json'));
            }
        }
        return $rolesArray;
    }

    /**
     * prepare given form values for JournalWorkflow nextSteps atrribute
     * @param array $nextSteps
     * @return array
     */
    protected function prepareNextsteps($nextSteps) {
        $repo = $this->get('doctrine_mongodb')->getManager()->getRepository('OjstrWorkflowBundle:JournalWorkflowStep');
        $nextStepsArray = array();
        if ($nextSteps) {
            foreach ($nextSteps as $stepId) {
                $step = $repo->find($stepId);
                $nextStepsArray[] = array('id' => $stepId, 'title' => $step->getTitle());
            }
        }
        return $nextStepsArray;
    }

    public function editAction(Request $request, $id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $selectedJournalId = $request->getSession()->get('selectedJournalId');
        $step = $dm->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')->find($id);
        $journal = $em->getRepository('OjstrJournalBundle:Journal')->findOneById($step->getJournalId());
        $roles = $em->getRepository('OjstrUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjstrWorkflowBundle:JournalWorkflowStep')
                ->findByJournalid($selectedJournalId);
        return $this->render('OjstrWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'roles' => $roles, 'nextSteps' => $nextSteps, 'journal' => $journal, 'step' => $step));
    }

    public function deleteAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjstrWorkflowBundle:JournalWorkflowStep');
        $step = $repo->find($id);
        $dm->remove($step);
        $dm->flush();
        return $this->redirect($this->generateUrl('manage_workflowsteps'));
    }

    public function showAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjstrWorkflowBundle:JournalWorkflowStep');
        $step = $repo->find($id);
        return $this->render('OjstrWorkflowBundle:WorkflowStep:show.html.twig', array('step' => $step));
    }

    public function updateAction(Request $request, $id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjstrWorkflowBundle:JournalWorkflowStep');
        /* @var $step \Ojstr\WorkflowBundle\Document\JournalWorkflowStep  */
        $step = $repo->find($id);
        $step->setTitle($request->get('title'));
        $step->setFirststep($request->get('firststep'));
        $step->setLaststep($request->get('laststep'));
        $step->setMaxdays($request->get('maxdays'));
        $step->setJournalid($request->get('journalId'));
        $step->setStatus($request->get('status'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        $step->setNextsteps($this->prepareNextsteps($request->get('nextsteps')));
        $dm->persist($step);
        $dm->flush();
        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $id)));
    }

}
