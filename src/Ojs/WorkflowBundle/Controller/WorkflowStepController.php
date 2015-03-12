<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class WorkflowStepController extends \Ojs\Common\Controller\OjsController {

    public function indexAction() {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        $steps = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journalid' => $selectedJournal->getId()));

        return $this->render('OjsWorkflowBundle:WorkflowStep:index.html.twig', array('steps' => $steps));
    }

    public function graphAction() {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        $data['steps'] = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journalid' => $selectedJournal->getId()));
        
        $data['firstStep'] = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findOneBy(array('journalid' => $selectedJournal->getId(), 'firstStep' => true));
        
        $data['lastStep'] = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findOneBy(array('journalid' => $selectedJournal->getId(), 'lastStep' => true));
        return $this->render('OjsWorkflowBundle:WorkflowStep:graph.html.twig',$data);
    }

    /**
     * render "new workflow" flow
     */
    public function newAction() {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();

        $em = $this->getDoctrine();
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findByJournalid($selectedJournal->getId());
        $journalReviewForms = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->getJournalForms($selectedJournal->getId());

        return $this->render('OjsWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'roles' => $roles,
                    'nextSteps' => $nextSteps,
                    'journal' => $selectedJournal,
                    'forms' => $journalReviewForms
        ));
    }

    /**
     * insert new step with data from "new workflow" form data
     */
    public function createAction(Request $request) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = new \Ojs\WorkflowBundle\Document\JournalWorkflowStep();
        $step->setMaxdays($request->get('maxdays'));
        $step->setFirststep($request->get('firstStep') ? true : false);
        $step->setLaststep($request->get('lastStep') ? true : false);
        $step->setJournalid($request->get('journalId'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        foreach($request->get('nextSteps') as $nId){
            $nextStep = $dm->getRepository()->find($nId);
            $step->addNextStep($nextStep);
        }
        $step->setOnlyreply($request->get('onlyreply') ? true : false);
        $step->setStatus($request->get('status'));
        $step->setTitle($request->get('title'));
        $step->setColor($request->get('color'));
        $step->setIsVisible($request->get('isVisible') ? true : false);
        $step->setMustBeAssigned($request->get('mustBeAssigned') ? true : false);
        $step->setCanEdit($request->get('canEdit') ? true : false);
        $step->setCanRejectSubmission($request->get('canRejectSubmission') ? true : false);
        $step->setCanReview($request->get('canReview') ? true : false);
        $step->setCanSeeAuthor($request->get('canSeeAuthor') ? true : false);
        $reviewFormIds = $request->get('reviewforms');
        if (!empty($reviewFormIds)) {
            foreach ($reviewFormIds as $formId) {
                $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formId);
                $form && $step->addReviewForm($form);
            }
        }
        $dm->persist($step);
        $dm->flush();

        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $step->getId())));
    }

    /**
     * prepare an array given form values for JournalWorkflow $roles atrribute
     * @param  array $roleIds 
     * @return array
     */
    public function prepareRoles($roleIds) {
        $serializer = $this->get('serializer');
        $em = $this->get('doctrine')->getManager();
        $roles = array();
        $rolesArray = array();
        if ($roleIds) {
            foreach ($roleIds as $roleId) {
                $roles[] = $em->getRepository("OjsUserBundle:Role")->findOneById($roleId);
            }
        }
        if ($roles) {
            foreach ($roles as $role) {
                $rolesArray[] = json_decode(
                        $serializer->serialize($role, 'json'));
            }
        }
        return $rolesArray;
    }

    public function editAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $journalReviewForms = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->getJournalForms($selectedJournal->getId());

        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneById($step->getJournalId());
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findByJournalid($selectedJournal->getId());

        return $this->render('OjsWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'roles' => $roles,
                    'nextSteps' => $nextSteps,
                    'journal' => $journal,
                    'step' => $step,
                    'forms' => $journalReviewForms
                        )
        );
    }

    public function deleteAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);
        $dm->remove($step);
        $dm->flush();

        return $this->redirect($this->generateUrl('manage_workflowsteps'));
    }

    public function showAction($id) {
        $step = $this->get('doctrine_mongodb')->getManager()
                        ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);

        return $this->render('OjsWorkflowBundle:WorkflowStep:show.html.twig', array('step' => $step));
    }

    public function updateAction(Request $request, $id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        /* @var $step \Ojs\WorkflowBundle\Document\JournalWorkflowStep  */
        $step = $repo->find($id);
        $step->setTitle($request->get('title'));
        $step->setFirststep($request->get('firstStep') ? true : false);
        $step->setLaststep($request->get('lastStep') ? true : false);
        $step->setMaxdays($request->get('maxdays'));
        $step->setJournalid($request->get('journalId'));
        $step->setColor($request->get('color'));
        $step->setStatus($request->get('status'));
        $step->removeAllReviewForms();
        $reviewFormIds = $request->get('reviewforms');
        if (!empty($reviewFormIds)) {
            foreach ($reviewFormIds as $formId) {
                $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formId);
                $form && $step->addReviewForm($form);
            }
        }
        $step->clearNextSteps();
        $step->setRoles($this->prepareRoles($request->get('roles')));
        foreach($request->get('nextSteps') as $nId){
            $nextStep = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($nId);
            $step->addNextStep($nextStep);
        }
        $step->setOnlyreply($request->get('onlyreply') ? true : false);
        $step->setIsVisible($request->get('isVisible') ? true : false);
        $step->setMustBeAssigned($request->get('mustBeAssigned') ? true : false);
        $step->setCanEdit($request->get('canEdit') ? true : false);
        $step->setCanRejectSubmission($request->get('canRejectSubmission') ? true : false);
        $step->setCanReview($request->get('canReview') ? true : false);
        $step->setCanSeeAuthor($request->get('canSeeAuthor'));
        $dm->persist($step);
        $dm->flush(); 


        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $id)));
    }

}
