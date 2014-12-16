<?php

namespace Ojs\JournalBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\JournalBundle\Form\MailTemplateType;

/**
 * MailTemplate controller.
 *
 */
class MailTemplateController extends Controller
{

    /**
     * Lists all MailTemplate entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsJournalBundle:MailTemplate')->findAll();

        return $this->render('OjsJournalBundle:MailTemplate:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new MailTemplate entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new MailTemplate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailtemplate_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:MailTemplate:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MailTemplate $entity)
    {
        $form = $this->createForm(new MailTemplateType(), $entity, array(
            'action' => $this->generateUrl('mailtemplate_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailTemplate entity.
     *
     */
    public function newAction()
    {
        $entity = new MailTemplate();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:MailTemplate:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MailTemplate entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        return $this->render('OjsJournalBundle:MailTemplate:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing MailTemplate entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:MailTemplate:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a MailTemplate entity.
     *
     * @param MailTemplate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(MailTemplate $entity)
    {
        $form = $this->createForm(new MailTemplateType(), $entity, array(
            'action' => $this->generateUrl('mailtemplate_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailTemplate entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('mailtemplate_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:MailTemplate:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a MailTemplate entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:MailTemplate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailTemplate entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('mailtemplate'));
    }

    

}
