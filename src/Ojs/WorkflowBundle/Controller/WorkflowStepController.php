<?php

namespace Ojs\WorkflowBundle\Controller;

use Ojs\Common\Controller\OjsController;
use Ojs\UserBundle\Entity\Role;
use Ojs\WorkflowBundle\Document\JournalWorkflowStep;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml;

class WorkflowStepController extends OjsController
{

    public function indexAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        $steps = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journalid' => $selectedJournal->getId()));

        return $this->render('OjsWorkflowBundle:WorkflowStep:index.html.twig', array('steps' => $steps));
    }

    public function graphAction()
    {
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

        return $this->render('OjsWorkflowBundle:WorkflowStep:graph.html.twig', $data);
    }

    /**
     * render "new workflow" flow
     */
    public function newAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();

        $em = $this->getDoctrine();
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journalid' => $selectedJournal->getId()));
        $journalReviewForms = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->getJournalForms($selectedJournal->getId());
        $yamlParser = new Yaml\Parser();
        $ciTemplates = $yamlParser->parse(file_get_contents(
                        $this->container->getParameter('kernel.root_dir').
                        '/../src/Ojs/JournalBundle/Resources/data/competingofinterest_templates.yml'
        ));
        /**
         *  @todo merge edit and new templates into one tpl.
         */
        return $this->render('OjsWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'roles' => $roles,
                    'nextSteps' => $nextSteps,
                    'journal' => $selectedJournal,
                    'forms' => $journalReviewForms,
                    'ciTemplates' => $ciTemplates,
        ));
    }

    /**
     * insert new step with data from "new workflow" form data
     *
     * @param  Request                                            $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function createAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = new JournalWorkflowStep();
        $step->setMaxDays($request->get('maxDays'));
        $step->setFirststep($request->get('firstStep') ? true : false);
        $step->setLaststep($request->get('lastStep') ? true : false);
        $step->setJournalid($request->get('journalId'));
        $step->setIntroduction($request->get('introduction'));
        $step->setRoles($this->prepareRoles($request->get('roles')));
        if (is_array($request->get('nextSteps'))) {
            foreach ($request->get('nextSteps') as $nId) {
                $nextStep = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($nId);
                $step->addNextStep($nextStep);
            }
        }
        $step->setOnlyreply($request->get('onlyreply') ? true : false);
        $step->setStatus($request->get('status'));
        $step->setTitle($request->get('title'));
        $step->setColor($request->get('color'));
        $step->setIsVisible($request->get('isVisible') ? true : false);
        $step->setMustBeAssigned($request->get('mustBeAssigned') ? true : false);
        $step->setShouldFileCi($request->get('shouldFileCi') ? true : false);
        $step->setCiText($request->get('ciText'));
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

        return $this->redirectToRoute('workflowsteps_show', [
            'id' => $step->getId(),
            ]
        );
    }

    /**
     * prepare an array given form values for JournalWorkflow $roles atrribute
     * @param  array  $roleIds
     * @return Role[]
     */
    private function prepareRoles($roleIds)
    {
        $serializer = $this->get('serializer');
        $em = $this->get('doctrine')->getManager();
        $roles = array();
        $rolesArray = array();
        if ($roleIds) {
            foreach ($roleIds as $roleId) {
                $roles[] = $em->getRepository("OjsUserBundle:Role")->find($roleId);
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

    public function editAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $journalReviewForms = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->getJournalForms($selectedJournal->getId());

        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($step->getJournalId());
        $roles = $em->getRepository('OjsUserBundle:Role')->findAll();
        $nextSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->findBy(array('journalid' => $selectedJournal->getId()));
        $yamlParser = new Yaml\Parser();

        return $this->render('OjsWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'roles' => $roles,
                    'nextSteps' => $nextSteps,
                    'journal' => $journal,
                    'step' => $step,
                    'forms' => $journalReviewForms,
                    'ciTemplates' => $yamlParser->parse(file_get_contents(
                                    $this->container->getParameter('kernel.root_dir').
                                    '/../src/Ojs/JournalBundle/Resources/data/competingofinterest_templates.yml'
                    )),
                        )
        );
    }

    /**
     * Removes given step. Also removes elements where added as stepNext
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $entity = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);
        // get where entity added as next step
        /** @var JournalWorkflowStep[] $steps */
        $steps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->createQueryBuilder()
                ->field('nextSteps.$id')
                ->equals(new \MongoId($entity->getId()))
                ->getQuery()
                ->execute();
        //remove where step is added as next step.
        foreach ($steps as $step) {
            $step->getNextSteps()->removeElement($entity);
        }
        //remove entity
        $dm->remove($entity);
        $dm->flush();

        $this->successFlashBag('Successfully removed');

        return $this->redirectToRoute('manage_workflowsteps');
    }

    public function showAction($id)
    {
        $step = $this->get('doctrine_mongodb')->getManager()
                        ->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($id);

        return $this->render('OjsWorkflowBundle:WorkflowStep:show.html.twig', array('step' => $step));
    }

    public function updateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep');
        /* @var $step \Ojs\WorkflowBundle\Document\JournalWorkflowStep  */
        $step = $repo->find($id);
        $step->setTitle($request->get('title'));
        $step->setFirststep($request->get('firstStep') ? true : false);
        $step->setLaststep($request->get('lastStep') ? true : false);
        $step->setMaxDays($request->get('maxDays'));
        $step->setJournalid($request->get('journalId'));
        $step->setColor($request->get('color'));
        $step->setStatus($request->get('status'));
        $step->setIntroduction($request->get('introduction'));
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
        $nextSteps = $request->get('nextSteps');
        if (!empty($nextSteps)) {
            foreach ($nextSteps as $nId) {
                $nextStep = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')->find($nId);
                $step->addNextStep($nextStep);
            }
        }
        $step->setOnlyreply($request->get('onlyreply') ? true : false);
        $step->setIsVisible($request->get('isVisible') ? true : false);
        $step->setMustBeAssigned($request->get('mustBeAssigned') ? true : false);
        $step->setShouldFileCi($request->get('shouldFileCi') ? true : false);
        $step->setCiText($request->get('ciText'));
        $step->setCanEdit($request->get('canEdit') ? true : false);
        $step->setCanRejectSubmission($request->get('canRejectSubmission') ? true : false);
        $step->setCanReview($request->get('canReview') ? true : false);
        $step->setCanSeeAuthor($request->get('canSeeAuthor'));
        $dm->persist($step);
        $dm->flush();

        $this->successFlashBag('Successfully updated');

        return $this->redirectToRoute('workflowsteps_show', [
            'id' => $id,
            ]
        );
    }
}
