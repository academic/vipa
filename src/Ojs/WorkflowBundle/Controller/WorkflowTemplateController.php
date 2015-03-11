<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class WorkflowTemplateController extends \Ojs\Common\Controller\OjsController {

    /**
     * @return Response
     */
    public function indexAction() {
        $templates = $this->get('doctrine_mongodb')->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->findAll();
        return $this->render('OjsWorkflowBundle:WorkflowStep:templates.html.twig', array('templates' => $templates));
    }

    /**
     * 
     * @param string $id template document id
     * @return Response
     */
    public function showAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($id);

        $steps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
                ->field('template.$id')
                ->equals(new \MongoId($template->getId()))
                ->getQuery()
                ->execute();
        return $this->render('OjsWorkflowBundle:WorkflowStep:template.html.twig', array('template' => $template, 'steps' => $steps));
    }

    /**
     * 
     * @param string $id template document id
     * @return RedirectResponse
     */
    public function useAction($id) {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($id);

        // remove old steps!
        /**
         * @todo
         * check each step and move all articles in review to first node
         */
        $collection = $dm->getDocumentCollection('OjsWorkflowBundle:JournalWorkflowStep');
        $collection->remove(array('journalid' => $selectedJournal->getId()));

        $steps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
                ->field('template.$id')
                ->equals(new \MongoId($template->getId()))
                ->getQuery()
                ->execute();
        $newSteps = [];
        foreach ($steps as $step) {
            $newSteps[$step->getId()] = $this->cloneStep($step, $newSteps);
        }
        // add nextstep relations
        foreach ($steps as $step) {
            $entity = $newSteps[$step->getId()]; 
            $nextSteps = $step->getNextSteps();
            foreach ($nextSteps as $nStep) {
                $entity->addNextStep($newSteps[$nStep->getId()]);
            } 
            $dm->persist($entity);
            $dm->flush();
        }
        /**
         * @todo
         * clone new steps and relate them 
         */
        return $this->redirect($this->generateUrl('ojs_workflow_homepage'));
    }

    /**
     * 
     * @param \Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $tplStep
     * @param array $newSteps
     * @return \Ojs\WorkflowBundle\Document\JournalWorkflowStep
     */
    protected function cloneStep(\Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $tplStep, $newSteps) {
        if (in_array($tplStep->getId(), $newSteps)) {
            return $newSteps[$tplStep->getId()];
        }
        $dm = $this->get('doctrine_mongodb')->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $step = new \Ojs\WorkflowBundle\Document\JournalWorkflowStep();
        $step->setJournalid($selectedJournal->getId());
        $step->setCanEdit($tplStep->getCanEdit());
        $step->getCanSeeAuthor($tplStep->getCanSeeAuthor());
        $step->setFirststep($tplStep->getFirststep());
        $step->setIsVisible($tplStep->getIsVisible());
        $step->setLaststep($tplStep->getLaststep());
        $step->setMaxdays($tplStep->getMaxdays());
        $step->setMustBeAssigned($tplStep->getMustBeAssigned());
        $step->setOnlyreply($tplStep->getOnlyreply());
        $step->setRoles($tplStep->getRoles());
        $step->setStatus($tplStep->getStatus());
        $step->setTitle($tplStep->getTitle());
        $step->setCanRejectSubmission($tplStep->getCanRejectSubmission());
        $step->setCanReview($tplStep->getCanReview());
        $step->setColor($tplStep->getColor());
        $step->clearNextSteps();
        $dm->persist($step);
        $dm->flush();
        return $step;
    }

}
