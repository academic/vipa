<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class ReviewFormItemController extends \Ojs\Common\Controller\OjsController
{

    /**
     * list review forms for selected journal
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        $forms = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewFormItem')
                ->findBy(array('journalid' => $selectedJournal->getId()));

        return $this->render('OjsWorkflowBundle:ReviewFormItem:index.html.twig', array('forms' => $forms));
    }

    /**
     * render "new review form" form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        return $this->render('OjsWorkflowBundle:ReviewFormItem:new.html.twig', array('journal' => $selectedJournal));
    }

    /**
     * insert new review form 
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = new \Ojs\WorkflowBundle\Document\ReviewFormItem();
        $form->setTitle($request->get('title'));
        $form->setMandotary($request->get('mandotary'));
        $form->setJournalid($selectedJournal->getId());
        $form->setInputType($request->get('inputtype'));
        // explode fields by new line and filter null values 
        $fields = array_filter(explode("\n", $request->get('fields')));
        $form->setFields($fields);
        $dm->persist($form);
        $dm->flush();

        return $this->redirect($this->generateUrl('ojs_review_form_items_show', array('id' => $form->getId()))
        );
    }

    /**
     * 
     * @param integer $id
     * @return Response
     */
    public function editAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        return $this->render('OjsWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'journal' => $selectedJournal, 'form' => $form)
        );
    }

    /**
     * 
     * @param integer $id
     * @return ResponseRedirect
     */
    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $dm->remove($form);
        $dm->flush();
        return $this->redirect($this->generateUrl('ojs_review_form_items')
        );
    }

    /**
     * 
     * @param integer $id
     * @return Response
     */
    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        return $this->render('OjsWorkflowBundle:ReviewFormItem:show.html.twig', array('form' => $form)
        );
    }

    /**
     * 
     * @param integer $id
     * @return ResponseRedirect
     */
    public function updateAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem');
        $form = $repo->find($id);
        $dm->persist($form);
        $dm->flush();
        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $id)));
    }

}
