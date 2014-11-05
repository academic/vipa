<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\ContactTypes;
use Ojs\JournalBundle\Form\ContactTypesType;

/**
 * ContactTypes controller.
 *
 */
class ContactTypesController extends Controller
{
    /**
     * Lists all ContactTypes entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:ContactTypes')->findAll();

        return $this->render('OjsJournalBundle:ContactTypes:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new ContactTypes entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ContactTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('contacttypes_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:ContactTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ContactTypes entity.
     *
     * @param ContactTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ContactTypes $entity)
    {
        $form = $this->createForm(new ContactTypesType(), $entity, array(
            'action' => $this->generateUrl('contacttypes_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ContactTypes entity.
     *
     */
    public function newAction()
    {
        $entity = new ContactTypes();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:ContactTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ContactTypes entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:ContactTypes:show.html.twig', array(
                    'entity' => $entity,));
    }

    /**
     * Displays a form to edit an existing ContactTypes entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:ContactTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a ContactTypes entity.
     *
     * @param ContactTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ContactTypes $entity)
    {
        $form = $this->createForm(new ContactTypesType(), $entity, array(
            'action' => $this->generateUrl('contacttypes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ContactTypes entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contacttypes_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:ContactTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a ContactTypes entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
            $this->throw404IfNotFound($entity);
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contacttypes'));
    }

}
