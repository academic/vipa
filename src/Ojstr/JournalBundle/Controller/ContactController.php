<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojstr\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\Contact;
use Ojstr\JournalBundle\Form\ContactType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * Contact controller.
 *
 */
class ContactController extends Controller {

    /**
     * Lists all Contact entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:Contact')->findAll();
        return $this->render('OjstrJournalBundle:Contact:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Contact entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Contact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('contact_show', array('id' => $entity->getId())));
        }
        return $this->render('OjstrJournalBundle:Contact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Contact entity.
     *
     * @param Contact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contact $entity) {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('contact_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     */
    public function newAction() {
        $entity = new Contact();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Contact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Contact')->find($id);
        $this->throw404IfNotFound($entity); 
        return $this->render('OjstrJournalBundle:Contact:show.html.twig', array(
                    'entity' => $entity, ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Contact')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity); 
        return $this->render('OjstrJournalBundle:Contact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(), 
        ));
    }

    /**
     * Creates a form to edit a Contact entity.
     *
     * @param Contact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Contact $entity) {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('contact_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Contact entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Contact')->find($id);
        $this->throw404IfNotFound($entity); 
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('contact_edit', array('id' => $id)));
        }
        return $this->render('OjstrJournalBundle:Contact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(), 
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:Contact')->find($id);
            $this->throw404IfNotFound($entity);
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('contact'));
    }
 

}
