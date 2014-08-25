<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\Attorney;
use Ojstr\UserBundle\Form\AttorneyType;

/**
 * Attorney controller.
 *
 */
class AttorneyController extends Controller {

    /**
     * Lists all Attorney entities.
     *
     */
    public function giveAction($targetUserId) {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->container->get('security.context')->getToken()->getUser();
        $attorneyUser = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->find($targetUserId);
        $attorney = new Attorney();
        $attorney->setAttorneyUser($attorneyUser);
        $attorney->setTargetUser($currentUser);
        $em->persist($attorney);
        $em->flush();
        $url = $this->getRequest()->headers->get("referer");
        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    }

    /**
     * Lists all Attorney entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrUserBundle:Attorney')->findAll();
        return $this->render('OjstrUserBundle:Attorney:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Attorney entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Attorney();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_attorney_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrUserBundle:Attorney:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Attorney entity.
     *
     * @param Attorney $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Attorney $entity) {
        $form = $this->createForm(new AttorneyType(), $entity, array(
            'action' => $this->generateUrl('admin_attorney_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Attorney entity.
     *
     */
    public function newAction() {
        $entity = new Attorney();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrUserBundle:Attorney:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Attorney entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Attorney')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attorney entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:Attorney:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Attorney entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Attorney')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attorney entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:Attorney:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Attorney entity.
     *
     * @param Attorney $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Attorney $entity) {
        $form = $this->createForm(new AttorneyType(), $entity, array(
            'action' => $this->generateUrl('admin_attorney_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Attorney entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Attorney')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attorney entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_attorney_edit', array('id' => $id)));
        }

        return $this->render('OjstrUserBundle:Attorney:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Attorney entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrUserBundle:Attorney')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Attorney entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_attorney'));
    }

    /**
     * Creates a form to delete a Attorney entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_attorney_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
