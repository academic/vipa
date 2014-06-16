<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojstr\UserBundle\Entity\EventLog;
use Ojstr\UserBundle\Form\EventLogType;

/**
 * EventLog controller.
 *
 */
class EventLogController extends Controller
{

    /**
     * Lists all EventLog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjstrUserBundle:EventLog')->findAll();

        return $this->render('OjstrUserBundle:EventLog:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EventLog entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EventLog();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventlog_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrUserBundle:EventLog:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EventLog entity.
    *
    * @param EventLog $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EventLog $entity)
    {
        $form = $this->createForm(new EventLogType(), $entity, array(
            'action' => $this->generateUrl('eventlog_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new EventLog entity.
     *
     */
    public function newAction()
    {
        $entity = new EventLog();
        $form   = $this->createCreateForm($entity);

        return $this->render('OjstrUserBundle:EventLog:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EventLog entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:EventLog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventLog entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:EventLog:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EventLog entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:EventLog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventLog entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjstrUserBundle:EventLog:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EventLog entity.
    *
    * @param EventLog $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EventLog $entity)
    {
        $form = $this->createForm(new EventLogType(), $entity, array(
            'action' => $this->generateUrl('eventlog_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing EventLog entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:EventLog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventLog entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('eventlog_edit', array('id' => $id)));
        }

        return $this->render('OjstrUserBundle:EventLog:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EventLog entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjstrUserBundle:EventLog')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EventLog entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eventlog'));
    }

    /**
     * Creates a form to delete a EventLog entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eventlog_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
