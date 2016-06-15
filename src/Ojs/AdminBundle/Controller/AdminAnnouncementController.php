<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Entity\AdminAnnouncement;
use Ojs\AdminBundle\Form\Type\AdminAnnouncementType;
use Ojs\CoreBundle\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class AdminAnnouncementController extends OjsController
{

    /**
     * Lists all AdminAnnouncement entities.
     */
    public function indexAction()
    {
        $source = new Entity('OjsAdminBundle:AdminAnnouncement');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_admin_announcement_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_announcement_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_announcement_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsAdminBundle:AdminAnnouncement:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Displays a form to create a new AdminAnnouncement entity.
     */
    public function newAction()
    {
        $entity = new AdminAnnouncement();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a AdminAnnouncement entity.
     *
     * @param AdminAnnouncement $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminAnnouncement $entity)
    {
        $form = $this->createForm(
            new AdminAnnouncementType(),
            $entity,
            ['action' => $this->generateUrl('ojs_admin_announcement_create'), 'method' => 'POST']
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new AdminAnnouncement entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new AdminAnnouncement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_announcement_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a AdminAnnouncement entity.
     *
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(AdminAnnouncement $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_announcement'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing AdminAnnouncement entity.
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(AdminAnnouncement $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_announcement'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:edit.html.twig',
            [
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to edit a AdminAnnouncement entity.
     *
     * @param  AdminAnnouncement $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AdminAnnouncement $entity)
    {
        $form = $this->createForm(
            new AdminAnnouncementType(),
            $entity,
            [
                'action' => $this->generateUrl('ojs_admin_announcement_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing AdminAnnouncement entity.
     * @param  Request $request
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, AdminAnnouncement $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_announcement_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, AdminAnnouncement $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_announcement'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }
        $deleteService = $this->get('ojs_core.delete.service');
        $deleteService->check($entity);

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_announcement_index');
    }
}
