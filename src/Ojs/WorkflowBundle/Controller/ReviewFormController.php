<?php

namespace Ojs\WorkflowBundle\Controller;

use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ORM\NoResultException;
use Ojs\Common\Controller\OjsController;
use Ojs\WorkflowBundle\Document\ReviewForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewFormController extends OjsController
{

    /**
     * list review forms for selected journal
     * @return Response
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
     * @return Response
     */
    public function newAction()
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();

        return $this->render('OjsWorkflowBundle:ReviewForm:new.html.twig', array('journal' => $selectedJournal));
    }

    /**
     * insert new review form
     * @param  Request          $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = new ReviewForm();
        $form->setTitle($request->get('title'));
        $form->setJournalid($selectedJournal->getId());
        $dm->persist($form);
        $dm->flush();

        $this->successFlashBag('successful.create');

        return $this->redirectToRoute('ojs_review_forms', [
            'id' => $form->getId(),
            ]
        );
    }

    /**
     *
     * @param  integer  $id
     * @return Response
     */
    public function editAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($id);

        return $this->render('OjsWorkflowBundle:ReviewForm:edit.html.twig', array(
                    'form' => $form, )
        );
    }

    /**
     *
     * @param  Request          $request
     * @param  string           $id
     * @return RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:ReviewForm');
        $form = $repo->find($id);
        $form->setTitle($request->get('title'));
        $dm->persist($form);
        $dm->flush();
        $this->successFlashBag('successful.update');

        return $this->redirect($this->generateUrl('ojs_review_forms_show', array('id' => $id)));
    }

    /**
     *
     * @param  integer           $id
     * @throws NoResultException
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($id);
        $this->throw404IfNotFound($form);
        $dm->remove($form);
        $dm->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_review_forms');
    }

    /**
     *
     * @param  integer           $id
     * @throws NoResultException
     * @return Response
     */
    public function showAction($id)
    {
        $form = $this->safeGet($id);

        return $this->render('OjsWorkflowBundle:ReviewForm:show.html.twig', array('form' => $form)
        );
    }

    /**
     *
     * @param  integer           $id
     * @throws NoResultException
     * @return Response
     */
    public function previewAction($id)
    {
        $form = $this->safeGet($id);
        $formItems = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewForm')
                ->getItemsGroupedByFieldset($form->getId());

        return $this->render('OjsWorkflowBundle:ReviewForm:preview.html.twig', array('formitems' => $formItems, 'form' => $form));
    }

    /**
     *
     * @param  string   $id
     * @return Response
     */
    public function itemsBlockAction($id)
    {
        $form = $this->safeGet($id);
        $formItems = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewForm')
                ->getItemsGroupedByFieldset($form->getId());

        return $this->render('OjsWorkflowBundle:ReviewForm:form_items_rendered.html.twig', array('formitems' => $formItems));
    }

    /**
     * @param $id
     * @return ReviewForm
     * @throws LockException
     */
    private function safeGet($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($id);
        $this->throw404IfNotFound($form);

        return $form;
    }
}
