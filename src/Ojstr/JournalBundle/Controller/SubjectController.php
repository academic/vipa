<?php

namespace Ojstr\JournalBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\JournalBundle\Entity\Subject;
use Ojstr\JournalBundle\Form\SubjectType;

/**
 * Subject controller.
 *
 */
class SubjectController extends Controller
{

    /**
     * Lists all Subject entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrJournalBundle:Subject')->findAll();

        return $this->render('OjstrJournalBundle:Subject:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Subject entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Subject();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_subject_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrJournalBundle:Subject:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Subject entity.
    *
    * @param Subject $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Subject $entity)
    {
        $form = $this->createForm(new SubjectType(), $entity, array(
            'action' => $this->generateUrl('admin_subject_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Subject entity.
     *
     */
    public function newAction()
    {
        $entity = new Subject();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjstrJournalBundle:Subject:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Subject entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:Subject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subject entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:Subject:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Subject entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:Subject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subject entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrJournalBundle:Subject:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Subject entity.
    *
    * @param Subject $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Subject $entity)
    {
        $form = $this->createForm(new SubjectType(), $entity, array(
            'action' => $this->generateUrl('admin_subject_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Subject entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrJournalBundle:Subject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Subject entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_subject_edit', array('id' => $id)));
        }

        return $this->render('OjstrJournalBundle:Subject:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Subject entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrJournalBundle:Subject')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Subject entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_subject'));
    }

    /**
     * Creates a form to delete a Subject entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_subject_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete','attr'=>array('onclick'=>'return confirm("Are you sure?");')))
            ->getForm()
        ;
    }
}
