<?php

namespace Ojstr\WorkflowBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\WorkflowBundle\Entity\WorkflowStep;
use Ojstr\WorkflowBundle\Form\WorkflowStepType;
use Ojstr\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * WorkflowStep controller.
 *
 */
class WorkflowStepController extends Controller {

    /**
     * Lists all WorkflowStep entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrWorkflowBundle:WorkflowStep')->findAll();

        return $this->render('OjstrWorkflowBundle:WorkflowStep:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new WorkflowStep entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new WorkflowStep();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('workflowstep_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a WorkflowStep entity.
     *
     * @param WorkflowStep $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(WorkflowStep $entity) {
        $form = $this->createForm(new WorkflowStepType(), $entity, array(
            'action' => $this->generateUrl('workflowstep_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new WorkflowStep entity.
     *
     */
    public function newAction() {
        $entity = new WorkflowStep();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrWorkflowBundle:WorkflowStep:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a WorkflowStep entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStep')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkflowStep entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrWorkflowBundle:WorkflowStep:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing WorkflowStep entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStep')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkflowStep entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a WorkflowStep entity.
     *
     * @param WorkflowStep $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(WorkflowStep $entity) {
        $form = $this->createForm(new WorkflowStepType(), $entity, array(
            'action' => $this->generateUrl('workflowstep_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing WorkflowStep entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStep')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkflowStep entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('workflowstep_edit', array('id' => $id)));
        }

        return $this->render('OjstrWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a WorkflowStep entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStep')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WorkflowStep entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('workflowstep'));
    }

    /**
     * Creates a form to delete a WorkflowStep entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        $formHelper = new CommonFormHelper();
        return $formHelper->createDeleteForm($this, $id, 'workflowstep_delete');
        /* $this->createFormBuilder()
          ->setAction($this->generateUrl('workflowstep_delete', array('id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm()
          ; */
    }

}
