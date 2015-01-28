<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class ReviewFormController extends \Ojs\Common\Controller\OjsController
{

    /**
     * list review forms for selected journal
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        $forms = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewForm')
                ->findBy(array('journalId' => $selectedJournal->getId()));

        return $this->render('OjsWorkflowBundle:ReviewForm:index.html.twig', array('forms' => $forms));
    }

    /**
     * render "new review form" form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        return $this->render('OjsWorkflowBundle:ReviewForm:new.html.twig', array('journal' => $selectedJournal));
    }

    /**
     * insert new review form
     * @param Request $request
     * @return ResponseRedirect
     */
    public function createAction(Request $request)
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = new \Ojs\WorkflowBundle\Document\ReviewForm();
        $form->setTitle($request->get('title'));
        $form->setJournalid($selectedJournal->getId());
        $dm->persist($form);
        $dm->flush();

        return $this->redirect($this->generateUrl('ojs_review_forms', array('id' => $form->getId()))
        );
    }

    /**
     * 
     * @param integer $id
     * @return Resnpose
     */
    public function editAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($id);
        return $this->render('OjsWorkflowBundle:ReviewForm:edit.html.twig', array(
                    'form' => $form)
        );
    }

    /**
     * 
     * @param Request $request
     * @param string $id
     * @return ResponseRedirect
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:ReviewForm');
        $form = $repo->find($id);
        $form->setTitle($request->get('title'));
        $dm->persist($form);
        $dm->flush();

        return $this->redirect($this->generateUrl('ojs_review_forms_show', array('id' => $id)));
    }

    /**
     * 
     * @param integer $id
     * @return ResponseRedirect
     */
    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($id);
        $dm->remove($form);
        $dm->flush();
        return $this->redirect($this->generateUrl('ojs_review_forms')
        );
    }

    /**
     * 
     * @param integer $id
     * @return ResponseRedirect
     */
    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($id);
        return $this->render('OjsWorkflowBundle:ReviewForm:show.html.twig', array('form' => $form)
        );
    }

}
