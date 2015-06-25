<?php

namespace Ojs\UserBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\Notification;
use Ojs\UserBundle\Form\Type\NotificationType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        if (!$this->isGranted('VIEW', new Notification())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsUserBundle:Notification');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];

        $rowAction[] = $gridAction->showAction('admin_notification_show', 'id');
        $rowAction[] = $gridAction->editAction('admin_notification_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('admin_notification_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsUserBundle:Notification:index.html.twig', $data);
    }

    /**
     * Creates a new Notification entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Notification())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
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

        return $this->render(
            'OjsUserBundle:Notification:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
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
        $form = $this->createForm(
            new NotificationType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('admin_notification_create'),
                'method' => 'POST',
            )
        );

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
        if (!$this->isGranted('CREATE', new Notification())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Notification();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsUserBundle:Notification:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Notification entity.
     *
     * @param  Notification $entity
     * @return Response
     */
    public function showAction(Notification $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        return $this->render(
            'OjsUserBundle:Notification:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Notification entity.
     *
     * @param  Notification $entity
     * @return Response
     */
    public function editAction(Notification $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsUserBundle:Notification:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
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
        $form = $this->createForm(
            new NotificationType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('admin_notification_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Notification entity.
     *
     * @param  Request                   $request
     * @param  Notification              $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Notification $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('admin_notification_edit', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsUserBundle:Notification:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Notification entity.
     *
     * @param  Notification     $entity
     * @return RedirectResponse
     */
    public function deleteAction(Notification $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_notification');
    }
}
