<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\MailLog;
use Ojs\AdminBundle\Form\Type\MailLogType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * MailLog controller.
 *
 */
class AdminMailLogController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new MailLog())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsUserBundle:MailLog');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];

        $rowAction[] = $gridAction->showAction('ojs_admin_mail_log_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_mail_log_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_mail_log_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminMailLog:index.html.twig', $data);
    }

    /**
     * Creates a new MailLog entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new MailLog())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new MailLog();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('ojs_admin_mail_log_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminMailLog:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a MailLog entity.
     *
     * @param  MailLog $entity The entity
     * @return Form    The form
     */
    private function createCreateForm(MailLog $entity)
    {
        $form = $this->createForm(
            new MailLogType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_mail_log_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MailLog entity.
     *
     * @return Response
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new MailLog())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new MailLog();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminMailLog:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a MailLog entity.
     * @param  MailLog  $entity
     * @return Response
     */
    public function showAction(MailLog $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        return $this->render(
            'OjsAdminBundle:AdminMailLog:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing MailLog entity.
     * @param  MailLog  $entity
     * @return Response
     */
    public function editAction(MailLog $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminMailLog:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a MailLog entity.
     * @param  MailLog $entity The entity
     * @return Form    The form
     */
    private function createEditForm(MailLog $entity)
    {
        $form = $this->createForm(
            new MailLogType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_mail_log_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MailLog entity.
     * @param  Request                   $request
     * @param  MailLog                   $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, MailLog $entity)
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

            return $this->redirectToRoute('ojs_admin_mail_log_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminMailLog:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a MailLog entity.
     * @param  MailLog          $entity
     * @return RedirectResponse
     */
    public function deleteAction(MailLog $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_admin_mail_log_index'));
    }
}
