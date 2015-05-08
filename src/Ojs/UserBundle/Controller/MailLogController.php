<?php

namespace Ojs\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;

use Ojs\UserBundle\Entity\MailLog;
use Ojs\UserBundle\Form\MailLogType;

/**
 * MailLog controller.
 *
 */
class MailLogController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:MailLog')->findAll();

        return $this->render('OjsUserBundle:MailLog:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new MailLog entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new MailLog();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');
            return $this->redirect($this->generateUrl('admin_maillog_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsUserBundle:MailLog:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MailLog entity.
     *
     * @param  MailLog                      $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MailLog $entity)
    {
        $form = $this->createForm(new MailLogType(), $entity, array(
            'action' => $this->generateUrl('admin_maillog_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailLog entity.
     *
     */
    public function newAction()
    {
        $entity = new MailLog();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:MailLog:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MailLog entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsUserBundle:MailLog:show.html.twig', array(
            'entity' => $entity
        ));
    }

    /**
     * Displays a form to edit an existing MailLog entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsUserBundle:MailLog:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Creates a form to edit a MailLog entity.
     * @param  MailLog                      $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(MailLog $entity)
    {
        $form = $this->createForm(new MailLogType(), $entity, array(
            'action' => $this->generateUrl('admin_maillog_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailLog entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirect($this->generateUrl('admin_maillog_edit', array('id' => $id)));
        }

        return $this->render('OjsUserBundle:MailLog:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a MailLog entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:MailLog')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');
        return $this->redirect($this->generateUrl('admin_maillog'));
    }
}
