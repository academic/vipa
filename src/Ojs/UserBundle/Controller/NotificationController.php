<?php

namespace Ojs\UserBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\Notification;
use Ojs\UserBundle\Form\NotificationType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Notification controller.
 *
 */
class NotificationController extends Controller
{
    /**
     * Lists all Notification entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:Notification')->findAll();

        return $this->render('OjsUserBundle:Notification:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Notification entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Notification();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('admin_notification_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsUserBundle:Notification:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Notification entity.
     *
     * @param Notification $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Notification $entity)
    {
        $form = $this->createForm(new NotificationType(), $entity, array(
            'action' => $this->generateUrl('admin_notification_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Notification entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new Notification();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:Notification:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Notification entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsUserBundle:Notification:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Notification entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Notification $entity */
        $entity = $em->getRepository('OjsUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsUserBundle:Notification:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Notification entity.
     *
     * @param Notification $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Notification $entity)
    {
        $form = $this->createForm(new NotificationType(), $entity, array(
            'action' => $this->generateUrl('admin_notification_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Notification entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Notification $entity */
        $entity = $em->getRepository('OjsUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('admin_notification_edit', array('id' => $id)));
        }

        return $this->render('OjsUserBundle:Notification:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Notification entity.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_notification');
    }
}
