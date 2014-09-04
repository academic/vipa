<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\JournalSection;
use Ojstr\JournalBundle\Form\JournalSectionType;

/**
 * JournalSection controller.
 *
 */
class JournalSectionController extends Controller {

    /**
     * Lists all JournalSection entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:JournalSection')->findAll();
        return $this->render('OjstrJournalBundle:JournalSection:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new JournalSection entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new JournalSection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_journal_section_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:JournalSection:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalSection entity.
     * @param JournalSection $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalSection $entity) {
        $form = $this->createForm(new JournalSectionType(), $entity, array(
            'action' => $this->generateUrl('admin_journal_section_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalSection entity.
     *
     */
    public function newAction() {
        $entity = new JournalSection();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrJournalBundle:JournalSection:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalSection entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:JournalSection:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing JournalSection entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:JournalSection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalSection entity.
     *
     * @param JournalSection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalSection $entity) {
        $form = $this->createForm(new JournalSectionType(), $entity, array(
            'action' => $this->generateUrl('admin_journal_section_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalSection entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_journal_section_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:JournalSection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a JournalSection entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:JournalSection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find JournalSection entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_journal_section'));
    }

    /**
     * Creates a form to delete a JournalSection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_journal_section_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
