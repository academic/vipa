<?php

namespace Ojstr\WorkflowBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojstr\WorkflowBundle\Entity\WorkflowStepRole;
use Ojstr\WorkflowBundle\Form\WorkflowStepRoleType;

/**
 * WorkflowStepRole controller.
 *
 */
class WorkflowStepRoleController extends Controller
{

    /**
     * Lists all WorkflowStepRole entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrWorkflowBundle:WorkflowStepRole')->findAll();

        return $this->render('OjstrWorkflowBundle:WorkflowStepRole:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new WorkflowStepRole entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new WorkflowStepRole();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('workflowsteprole_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrWorkflowBundle:WorkflowStepRole:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a WorkflowStepRole entity.
    *
    * @param WorkflowStepRole $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(WorkflowStepRole $entity)
    {
        $form = $this->createForm(new WorkflowStepRoleType(), $entity, array(
            'action' => $this->generateUrl('workflowsteprole_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new WorkflowStepRole entity.
     *
     */
    public function newAction()
    {
        $entity = new WorkflowStepRole();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjstrWorkflowBundle:WorkflowStepRole:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a WorkflowStepRole entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStepRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkflowStepRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrWorkflowBundle:WorkflowStepRole:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing WorkflowStepRole entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStepRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkflowStepRole entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrWorkflowBundle:WorkflowStepRole:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a WorkflowStepRole entity.
    *
    * @param WorkflowStepRole $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(WorkflowStepRole $entity)
    {
        $form = $this->createForm(new WorkflowStepRoleType(), $entity, array(
            'action' => $this->generateUrl('workflowsteprole_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing WorkflowStepRole entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStepRole')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WorkflowStepRole entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('workflowsteprole_edit', array('id' => $id)));
        }

        return $this->render('OjstrWorkflowBundle:WorkflowStepRole:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a WorkflowStepRole entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrWorkflowBundle:WorkflowStepRole')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WorkflowStepRole entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('workflowsteprole'));
    }

    /**
     * Creates a form to delete a WorkflowStepRole entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('workflowsteprole_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
