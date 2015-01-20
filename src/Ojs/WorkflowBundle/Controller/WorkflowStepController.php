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
        $step->setFirststep($request->get('firststep') ? true : false);
        $step->setLaststep($request->get('laststep') ? true : false);
        $step->setJournalid($request->get('journalId'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        $step->setNextsteps($this->prepareNextsteps($request->get('nextsteps')));
        $step->setStatus($request->get('status'));
        $step->setTitle($request->get('title'));
        $step->setIsVisible($request->get('isVisible') ? true : false);
        $step->setCanEdit($request->get('canEdit') ? true : false);
        $step->setCanSeeAuthor($request->get('canSeeAuthor') ? true : false);
        $dm->persist($step);
        $dm->flush();

        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $step->getId())));
    }

    /**
     * prepare an array given form values for JournalWorkflow $roles atrribute
     * @param Symfony\Component\Serializer\Serializer $serializer
     * @param  array $roles
     * @return array
     */
    public function prepareRoles($serializer, $roles)
    {
        $rolesArray = array();
        if ($roles) {
            foreach ($roles as $role) {
                $rolesArray[] = json_decode(
                        $serializer->serialize($role, 'json'));
            }
        }
        return $rolesArray;
    }

    /**
     * prepare an array from given form values for JournalWorkflow nextSteps atrribute
     * @param  array $nextSteps
     * @return array
     */
    public function prepareNextsteps($nextSteps)
    {
        $nextStepsArray = array();
        if ($nextSteps) {
            foreach ($nextSteps as $step) {
                $nextStepsArray[] = array(
                    'id' => $step->getId(),
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
        $em = $this->getDoctrine()->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        /* @var $step \Ojs\WorkflowBundle\Document\JournalWorkflowStep  */
        $step = $repo->find($id);
        $step->setTitle($request->get('title'));
        $step->setFirststep($request->get('firststep') ? true : false);
        $step->setLaststep($request->get('laststep') ? true : false);
        $step->setMaxdays($request->get('maxdays'));
        $step->setJournalid($request->get('journalId'));
        $step->setStatus($request->get('status'));
        $roleIds = $request->get('roles');
        $rolesArray = array();
        if ($roleIds) {
            foreach ($roleIds as $roleId) {
                $rolesArray[] = $em->getRepository("OjsUserBundle:Role")->findOneById($roleId);
            }
        }
        $step->setRoles($this->prepareRoles($this->container->get('serializer'), $rolesArray));
        $nextStepIds = $request->get('nextsteps');
        $nextStepsArray = array();
        if ($nextStepIds) {
            foreach ($nextStepIds as $nextStepId) {
                $nextStepsArray[] = $dm->getRepository("OjsWorkflowBundle:Workflow")->findOneById($nextStepId);
            }
        }

        $step->setNextsteps($this->prepareNextsteps($nextStepsArray));
        $step->setOnlyreply($request->get('onlyreply') ? true : false);
        $step->setIsVisible($request->get('isVisible') ? true : false);
        $step->setCanEdit($request->get('canEdit') ? true : false);
        $step->setCanSeeAuthor($request->get('canSeeAuthor'));
        $dm->persist($step);
        $dm->flush();

        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $id)));
    }

}
