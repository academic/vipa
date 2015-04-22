<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Entity\MailType;
use Ojs\JournalBundle\Form\MailTypeType;

/**
 * MailType controller.
 *
 */
class MailTypeController extends Controller
{

    /**
     * Lists all MailType entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsJournalBundle:MailType')->findAll();

        return $this->render('OjsJournalBundle:MailType:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new MailType entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new MailType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_mailtype_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:MailType:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MailType entity.
     *
     * @param MailType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MailType $entity)
    {
        $form = $this->createForm(new MailTypeType(), $entity, array(
            'action' => $this->generateUrl('admin_mailtype_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailType entity.
     *
     */
    public function newAction()
    {
        $entity = new MailType();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:MailType:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MailType entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:MailType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:MailType:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing MailType entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:MailType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:MailType:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a MailType entity.
     *
     * @param MailType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(MailType $entity)
    {
        $form = $this->createForm(new MailTypeType(), $entity, array(
            'action' => $this->generateUrl('admin_mailtype_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailType entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:MailType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_mailtype_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:MailType:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a MailType entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:MailType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_mailtype'));
    } 

}
