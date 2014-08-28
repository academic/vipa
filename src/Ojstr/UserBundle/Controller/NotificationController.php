<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\Notification;
use Ojstr\UserBundle\Form\NotificationType;

/**
 * Notification controller.
 *
 */
class NotificationController extends Controller {
 

    /**
     * Lists all Notification entities.
     *
     */
    public function indexAction() { 
        $entities = $em->getRepository('OjstrUserBundle:Notification')->findAll();
        return $this->render('OjstrUserBundle:Notification:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Notification entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Notification();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_notification_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrUserBundle:Notification:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Notification entity.
     *
     * @param Notification $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Notification $entity) {
        $form = $this->createForm(new NotificationType(), $entity, array(
            'action' => $this->generateUrl('admin_notification_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Notification entity.
     *
     */
    public function newAction() {
        $entity = new Notification();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrUserBundle:Notification:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Notification entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        return $this->render('OjstrUserBundle:Notification:show.html.twig', array(
                    'entity' => $entity
        ));
    }

    /**
     * Displays a form to edit an existing Notification entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjstrUserBundle:Notification:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Notification entity.
     *
     * @param Notification $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Notification $entity) {
        $form = $this->createForm(new NotificationType(), $entity, array(
            'action' => $this->generateUrl('admin_notification_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Notification entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_notification_edit', array('id' => $id)));
        }

        return $this->render('OjstrUserBundle:Notification:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Notification entity.
     *
     */
    public function deleteAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_notification'));
    }

}
