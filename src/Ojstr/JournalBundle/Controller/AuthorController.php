<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojstr\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\Author;
use Ojstr\JournalBundle\Form\AuthorType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * Author controller.
 *
 */
class AuthorController extends Controller {

    /**
     * Lists all Author entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:Author')->findAll();
        return $this->render('OjstrJournalBundle:Author:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Author entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Author();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('author_show', array('id' => $entity->getId())));
        }
        return $this->render('OjstrJournalBundle:Author:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Author entity.
     *
     * @param Author $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Author $entity) {
        $form = $this->createForm(new AuthorType(), $entity, array(
            'action' => $this->generateUrl('author_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new Author entity.
     *
     */
    public function newAction() {
        $entity = new Author();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:Author:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Author entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        return $this->render('OjstrJournalBundle:Author:show.html.twig', array(
                    'entity' => $entity));
    }

    /**
     * Displays a form to edit an existing Author entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        return $this->render('OjstrJournalBundle:Author:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a Author entity.
     *
     * @param Author $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Author $entity) {
        $form = $this->createForm(new AuthorType(), $entity, array(
            'action' => $this->generateUrl('author_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing Author entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('author_edit', array('id' => $id)));
        }
        return $this->render('OjstrJournalBundle:Author:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Author entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:Author')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('author'));
    }

}
