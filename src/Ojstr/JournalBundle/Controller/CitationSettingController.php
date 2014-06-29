<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojstr\Common\Controller\OjsController as Controller;
use Ojstr\JournalBundle\Entity\CitationSetting;
use Ojstr\JournalBundle\Form\CitationSettingType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * CitationSetting controller.
 *
 */
class CitationSettingController extends Controller {

    /**
     * Lists all CitationSetting entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrJournalBundle:CitationSetting')->findAll();

        return $this->render('OjstrJournalBundle:CitationSetting:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new CitationSetting entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new CitationSetting();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('citationsetting_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:CitationSetting:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CitationSetting entity.
     *
     * @param CitationSetting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CitationSetting $entity) {
        $form = $this->createForm(new CitationSettingType(), $entity, array(
            'action' => $this->generateUrl('citationsetting_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CitationSetting entity.
     *
     */
    public function newAction() {
        $entity = new CitationSetting();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrJournalBundle:CitationSetting:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CitationSetting entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:CitationSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CitationSetting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:CitationSetting:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CitationSetting entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:CitationSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CitationSetting entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:CitationSetting:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CitationSetting entity.
     *
     * @param CitationSetting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CitationSetting $entity) {
        $form = $this->createForm(new CitationSettingType(), $entity, array(
            'action' => $this->generateUrl('citationsetting_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CitationSetting entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:CitationSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CitationSetting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('citationsetting_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:CitationSetting:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CitationSetting entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:CitationSetting')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CitationSetting entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('citationsetting'));
    }

    /**
     * Creates a form to delete a CitationSetting entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        $formHelper = new CommonFormHelper();
        return $formHelper->createDeleteForm($this, $id,'citationsetting_delete');
    }

}
