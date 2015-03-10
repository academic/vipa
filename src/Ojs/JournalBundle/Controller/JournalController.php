<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Mapping\Column;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\JournalType;
use Ojs\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * Journal controller.
 */
class JournalController extends Controller
{

    public function changeSelectedAction(Request $request, $journal_id)
    {
        $referer = $request->headers->get('referer');
        $request->getSession()->set('selectedJournalId', $journal_id);

        return $this->redirect($referer);
    }

    /**
     * Lists all Journal entities.
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:Journal');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('journal_show', 'id');
        $rowAction[] = ActionHelper::editAction('journal_edit', 'id');
        $rowAction[] = ActionHelper::cmsAction();
        $rowAction[] = ActionHelper::deleteAction('journal_delete', 'id');

        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Journal:index.html.twig',$data);
    }

    /**
     * Creates a new Journal entity.
     */
    public function createAction(Request $request)
    {
        $entity = new Journal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            /** @var DocumentManager $dm */
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            $logo = $request->request->get('logo');
            $ir = $dm->getRepository('OjsSiteBundle:ImageOptions');
            $imageOptions = $ir->init($header,$entity,'header');
            $dm->persist($imageOptions);
            $imageOptions = $ir->init($cover,$entity,'cover');
            $dm->persist($imageOptions);
            $imageOptions = $ir->init($logo,$entity,'logo');
            $dm->persist($imageOptions);
            $dm->flush();
            return $this->redirect($this->generateUrl('journal_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:Journal:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Journal entity.
     * @param Journal $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Journal $entity)
    {
        $form = $this->createForm(new JournalType(), $entity, array(
            'action' => $this->generateUrl('journal_create'),
            'method' => 'POST',
        ));
        return $form;
    }

    /**
     * Displays a form to create a new Journal entity.
     */
    public function newAction()
    {
        $entity = new Journal();
        $form = $this->createCreateForm($entity);
        return $this->render('OjsJournalBundle:Journal:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Journal entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        return $this->render('OjsJournalBundle:Journal:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing Journal entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Journal:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Journal entity.
     * @param Journal $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Journal $entity)
    {
        $form = $this->createForm(new JournalType(), $entity, array(
            'action' => $this->generateUrl('journal_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));
        return $form;
    }

    /**
     * Edits an existing Journal entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            /** @var DocumentManager $dm */
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            $logo = $request->request->get('logo');
            $ir = $dm->getRepository('OjsSiteBundle:ImageOptions');
            $imageOptions = $ir->init($header,$entity,'header');
            $dm->persist($imageOptions);
            $imageOptions = $ir->init($cover,$entity,'cover');
            $dm->persist($imageOptions);
            $imageOptions = $ir->init($logo,$entity,'logo');
            $dm->persist($imageOptions);
            $dm->flush();
            $em->flush();
            return $this->redirect($this->generateUrl('journal_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Journal:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Journal entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('journal'));
    }

    public function applyAction()
    {
        return $this->render('OjsJournalBundle:Journal:apply.html.twig', array());
    }

}
