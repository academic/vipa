<?php

namespace Ojstr\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\InstitutionTypes;
use Ojstr\JournalBundle\Form\InstitutionTypesType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * InstitutionTypes controller.
 *
 */
class InstitutionTypesController extends Controller {

    /**
     * Lists all InstitutionTypes entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrJournalBundle:InstitutionTypes')->findAll();
        return $this->render('OjstrJournalBundle:InstitutionTypes:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new InstitutionTypes entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('institution_types_show', array('id' => $entity->getId())));
        }
        return $this->render('OjstrJournalBundle:InstitutionTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a InstitutionTypes entity.
     *
     * @param InstitutionTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InstitutionTypes $entity) {
        $form = $this->createForm(new InstitutionTypesType(), $entity, array(
            'action' => $this->generateUrl('institution_types_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new InstitutionTypes entity.
     *
     */
    public function newAction() {
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);
        return $this->render('OjstrJournalBundle:InstitutionTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InstitutionTypes entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:InstitutionTypes')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:InstitutionTypes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing InstitutionTypes entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:InstitutionTypes')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('OjstrJournalBundle:InstitutionTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a InstitutionTypes entity.
     *
     * @param InstitutionTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(InstitutionTypes $entity) {
        $form = $this->createForm(new InstitutionTypesType(), $entity, array(
            'action' => $this->generateUrl('institution_types_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing InstitutionTypes entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrJournalBundle:InstitutionTypes')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('institution_types_edit', array('id' => $id)));
        }
        return $this->render('OjstrJournalBundle:InstitutionTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a InstitutionTypes entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:InstitutionTypes')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('institution_types'));
    }

    /**
     * Creates a form to delete a InstitutionTypes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        $formHelper = new CommonFormHelper();
        return $formHelper->createDeleteForm($this, $id);
    }

}
