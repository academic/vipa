<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojstr\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\Journal;
use Ojstr\JournalBundle\Form\JournalType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

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
        $entities = $this->getDoctrine()->getManager()->getRepository('OjstrJournalBundle:Journal')->findAll();
        return $this->render('OjstrJournalBundle:Journal:index.html.twig', array(
                    'entities' => $entities,
        ));
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
            return $this->redirect($this->generateUrl('journal_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:Journal:new.html.twig', array(
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
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Journal entity.
     */
    public function newAction()
    {
        $entity = new Journal();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Journal:new.html.twig', array(
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
        $entity = $em->getRepository('OjstrJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        return $this->render('OjstrJournalBundle:Journal:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing Journal entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        return $this->render('OjstrJournalBundle:Journal:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
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
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Journal entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('journal_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:Journal:edit.html.twig', array(
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
        $entity = $em->getRepository('OjstrJournalBundle:Journal')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('journal'));
    }
    
    public function applyAction()
    {
        return $this->render('OjstrJournalBundle:Journal:apply.html.twig', array());
    }

}
