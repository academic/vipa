<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class WorkflowStepController extends \Ojs\Common\Controller\OjsController
{

    public function indexAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$selectedJournal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $steps = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journalid' => $selectedJournal->getId()));

        return $this->render('OjsWorkflowBundle:WorkflowStep:index.html.twig', array('steps' => $steps));
    }

    /**
     * render "new workflow" flow
     */
    public function newAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();
        if (!$selectedJournal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->get('doctrine');
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findByJournalid($selectedJournal->getId());

        return $this->render('OjsWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'roles' => $roles, 'nextSteps' => $nextSteps, 'journal' => $selectedJournal));
    }

    /**
     * insert new step with data from "new workflow" form data
     */
    public function createAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = new \Ojs\WorkflowBundle\Document\JournalWorkflowStep();
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
     * @param  array $roles
     * @return array
     */
    protected function prepareRoles($roles)
    {
        $serializer = $this->container->get('serializer');
        $em = $this->getDoctrine()->getManager();
        $rolesArray = array();
        if ($roles) {
            foreach ($roles as $roleId) {
                $rolesArray[] = json_decode($serializer->serialize($em->getRepository("OjsUserBundle:Role")->findOneById($roleId), 'json'));
            }
        }

        return $rolesArray;
    }

    /**
     * prepare given form values for JournalWorkflow nextSteps atrribute
     * @param  array $nextSteps
     * @return array
     */
    protected function prepareNextsteps($nextSteps)
    {
        $repo = $this->get('doctrine_mongodb')->getManager()->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        $nextStepsArray = array();
        if ($nextSteps) {
            foreach ($nextSteps as $stepId) {
                $step = $repo->find($stepId);
                $nextStepsArray[] = array(
                    'id' => $stepId,
                    'title' => $step->getTitle());
            }
        }

        return $nextStepsArray;
    }

    public function editAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$selectedJournal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneById($step->getJournalId());
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findByJournalid($selectedJournal->getId());

        return $this->render('OjsWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'roles' => $roles, 'nextSteps' => $nextSteps, 'journal' => $journal, 'step' => $step));
    }

    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        $step = $repo->find($id);
        $dm->remove($step);
        $dm->flush();

        return $this->redirect($this->generateUrl('manage_workflowsteps'));
    }

    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        $step = $repo->find($id);

        return $this->render('OjsWorkflowBundle:WorkflowStep:show.html.twig', array('step' => $step));
    }

    public function updateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        /* @var $step \Ojs\WorkflowBundle\Document\JournalWorkflowStep  */
        $step = $repo->find($id);
        $step->setTitle($request->get('title'));
        $step->setFirststep($request->get('firststep'));
        $step->setLaststep($request->get('laststep'));
        $step->setMaxdays($request->get('maxdays'));
        $step->setJournalid($request->get('journalId'));
        $step->setStatus($request->get('status'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        $step->setNextsteps($this->prepareNextsteps($request->get('nextsteps')));
        $step->setOnlyReply($request->get('onlyreply'));
        $dm->persist($step);
        $dm->flush();

        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $id)));
    }

}
