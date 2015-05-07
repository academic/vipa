<?php

namespace Ojs\WorkflowBundle\Controller;

use Ojs\WorkflowBundle\Form\TemplateType;
use Ojs\WorkflowBundle\Document\JournalWorkflowTemplate;
use \Symfony\Component\HttpFoundation\Request;
use Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep;
use \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class WorkflowTemplateController extends \Ojs\Common\Controller\OjsController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        /**
         * Get system and journal's templates
         * Query references below
         * @link http://doctrine-mongodb-odm.readthedocs.org/en/latest/reference/query-builder-api.html#map-reduce
         * @link http://docs.mongodb.org/manual/reference/operator/query/and/#op._S_and
         */
        $templates = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->createQueryBuilder()
            ->where("function() { return this.isSystemTemplate == true || this.journalId == ".$selectedJournal->getId()." }")
            ->getQuery()
            ->execute();

        return $this->render('OjsWorkflowBundle:WorkflowStep:Template/templates.html.twig', array(
            'templates' => $templates
        ));
    }

    /**
     *
     * @param string $id template document id
     * @return Response
     */
    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($id);

        $steps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
            ->field('template.$id')
            ->equals(new \MongoId($template->getId()))
            ->getQuery()
            ->execute();
        return $this->render('OjsWorkflowBundle:WorkflowStep:Template/template.html.twig', array('template' => $template, 'steps' => $steps));
    }

    /**
     *
     * @param string $id template document id
     * @return RedirectResponse
     */
    public function useAction($id)
    {
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
    protected function cloneStep(\Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $tplStep, $newSteps)
    {
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

    /**
     * New workflow template create
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new JournalWorkflowTemplate();

        $form = $this->createForm(new TemplateType(), $entity, array(
            'action' => $this->generateUrl('workflow_template_create'),
            'method' => 'POST',
        ));

        return $this->render('OjsWorkflowBundle:WorkflowStep:Template/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Insert template data that comes from post data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $session = $request->getSession();
        $entity = new JournalWorkflowTemplate();
        $form = $this->createForm(new TemplateType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dm = $this->get('doctrine_mongodb')->getManager();
            //Template created by user
            $entity->setIsSystemTemplate(0);
            $entity->setJournalId($selectedJournal->getId());
            $dm->persist($entity);
            $dm->flush();

            $this->successFlashBag('Template created successfully. Now you can add steps');
            return $this->redirectToRoute('workflow_template_new_step', [
                'templateId' => $entity->getId()
                ]
            );
        }

        //add error message
        $session->getFlashBag()->add(
            'error',
            'Please recheck your submission'
        );
        return $this->redirect($this->generateUrl('workflow_template_new'));
    }

    /**
     * Edit created template. If user try edit system template throws forbidden exception
     * @param $templateId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($templateId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($templateId);

        //control if is not system template
        if($template->getIsSystemTemplate() == 1)
            throw new AccessDeniedHttpException();

        $form = $this->createForm(new TemplateType(), $template, array(
            'action' => $this->generateUrl('workflow_template_update', array('templateId' =>$templateId)),
            'method' => 'POST',
        ));

        return $this->render('OjsWorkflowBundle:WorkflowStep:Template/edit.html.twig', array(
            'form' => $form->createView(),
            'template' => $template
        ));
    }

    /**
     * @param Request $request
     * @param $templateId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request,$templateId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $entity = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($templateId);
        $session = $request->getSession();
        $form = $this->createForm(new TemplateType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dm->persist($entity);
            $dm->flush();

            $this->successFlashBag('Template updated successfully.');
            return $this->redirectToRoute('workflow_template_show', [
                'id' => $entity->getId()
                ]
            );
        }
        $this->errorFlashBag('Please recheck your submission');
        return $this->redirectToRoute('workflow_template_edit', [
            'templateId' => $entity->getId()
            ]
        );
    }

    /**
     * Deletes created template. If user try edit system template throws forbidden exception.
     * @param Request $request
     * @param $templateId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$templateId)
    {
        $session = $request->getSession();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($templateId);
        $dm->remove($template);

        //control if is not system template
        if($template->getIsSystemTemplate() == 1)
            throw new AccessDeniedHttpException();

        $templateSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
            ->field('template.$id')
            ->equals(new \MongoId($template->getId()))
            ->getQuery()
            ->execute();
        //remove all template's steps
        foreach($templateSteps as $step){
            $dm->remove($step);
        }
        $dm->flush();

        $this->successFlashBag('Template successfully removed');
        return $this->redirectToRoute('workflow_templates');
    }

    /**
     * add new step to created workflow template
     * @param $templateId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newStepAction($templateId)
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($templateId);

        $em = $this->getDoctrine();
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();

        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
            ->field('template.$id')
            ->equals(new \MongoId($template->getId()))
            ->getQuery()
            ->execute();

        $journalReviewForms = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->getJournalForms($selectedJournal->getId());

        return $this->render('OjsWorkflowBundle:WorkflowStep/Template:new_step.html.twig', array(
            'roles' => $roles,
            'nextSteps' => $nextSteps,
            'journal' => $selectedJournal,
            'forms' => $journalReviewForms,
            'template' => $template
        ));
    }

    /**
     * @param Request $request
     * @param $templateId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createStepAction(Request $request, $templateId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($templateId);
        $step = new JournalWorkflowTemplateStep();
        $step->setTemplate($template);
        $step->setMaxdays($request->get('maxdays'));
        $step->setFirststep($request->get('firstStep') ? true : false);
        $step->setLaststep($request->get('lastStep') ? true : false);
        $step->setJournalid($request->get('journalId'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        if(is_array($request->get('nextSteps')))
            foreach($request->get('nextSteps') as $nId){
                $nextStep = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->find($nId);
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

        $this->successFlashBag('Successfully created');
        return $this->redirectToRoute('workflow_template_show', [
            'id' => $templateId
            ]
        );
    }

    /**
     * @param $stepId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showStepAction($stepId)
    {
        $step = $this->get('doctrine_mongodb')->getManager()
            ->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->find($stepId);

        return $this->render('OjsWorkflowBundle:WorkflowStep/Template:show_step.html.twig', array('step' => $step));
    }

    /**
     * @param $stepId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editStepAction($stepId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $journalReviewForms = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->getJournalForms($selectedJournal->getId());

        /**
         * @var \Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep $step
         */
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->find($stepId);
        //control if steps template system template
        if($step->getTemplate()->getIsSystemTemplate())
            throw new AccessDeniedHttpException();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneById($step->getJournalId());
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')
            ->findByJournalid($selectedJournal->getId());

        return $this->render('OjsWorkflowBundle:WorkflowStep/Template:edit_step.html.twig', array(
                'roles' => $roles,
                'nextSteps' => $nextSteps,
                'journal' => $journal,
                'step' => $step,
                'forms' => $journalReviewForms
            )
        );
    }

    /**
     * @param Request $request
     * @param $stepId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateStepAction(Request $request, $stepId)
    {
        $session = $request->getSession();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->find($stepId);
        /**
         * @var $step \Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep
         */
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
        //empty nextSteps parameter
        $step->setNextSteps(array());
        $step->setRoles($this->prepareRoles($request->get('roles')));
        $nextSteps = $request->get('nextSteps');
        if (is_array($nextSteps) && !empty($nextSteps)) {
            foreach ($nextSteps as $nId) {
                $nextStep = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->find($nId);
                $step->addNextStep($nextStep);
            }
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

        $this->successFlashBag('Template step updated successfully');
        return $this->redirectToRoute('workflow_template_step_show', [
            'stepId' => $stepId
            ]
        );
    }

    /**
     * removes given template step. Alse removes element where added as nextStep
     * @param Request $request
     * @param $stepId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteStepAction(Request $request,$stepId)
    {
        $session = $request->getSession();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $entity = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->find($stepId);
        //control if steps template system template
        if($entity->getTemplate()->getIsSystemTemplate())
            throw new AccessDeniedHttpException();
        $template = $entity->getTemplate();
        // get where entity added as next step
        $steps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
                ->field('nextSteps.$id')
                ->equals(new \MongoId($entity->getId()))
                ->getQuery()
                ->execute();
        //remove where step is added as next step.
        foreach($steps as $step)
            $step->getNextSteps()->removeElement($entity);
        //remove entity
        $dm->remove($entity);
        $dm->flush();

        $this->successFlashBag('Step removed successfully');
        return $this->redirectToRoute('workflow_template_show', [
            'id' => $template->getId()
            ]
        );
    }

    /**
     * prepare an array given form values for JournalWorkflowTemplate $roles atrribute
     * @param  array $roleIds
     * @return array
     */
    public function prepareRoles($roleIds)
    {
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

}
