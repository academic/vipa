<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\AdminBundle\Entity\AdminAnnouncement;
use Ojs\CmsBundle\Form\Type\AnnouncementType;
use Ojs\Common\Controller\OjsController;
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
        if (!$this->isGranted('VIEW', new AdminAnnouncement())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $source = new Entity('OjsAdminBundle:AdminAnnouncement');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

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
        if (!$this->isGranted('CREATE', new AdminAnnouncement())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new AdminAnnouncement();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a new AdminAnnouncement entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new AdminAnnouncement())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

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
     * Creates a form to create a AdminAnnouncement entity.
     *
     * @param AdminAnnouncement $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminAnnouncement $entity)
    {
        $form = $this->createForm(new AnnouncementType(), $entity,
            ['action' => $this->generateUrl('ojs_admin_announcement_create'), 'method' => 'POST']);
        $form->add('submit', 'submit', ['label' => 'Create']);
        return $form;
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

        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_announcement'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminAnnouncement:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Lang entity.
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(AdminAnnouncement $entity)
    {
        $this->throw404IfNotFound($entity);
        
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

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
     * Edits an existing Lang entity.
     * @param  Request $request
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, AdminAnnouncement $entity)
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
     * Creates a form to edit a Lang entity.
     *
     * @param  AdminAnnouncement $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AdminAnnouncement $entity)
    {
        $form = $this->createForm(new AnnouncementType(), $entity,
            [
                'action' => $this->generateUrl('ojs_admin_announcement_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);
        return $form;
    }

    /**
     * @param  Request $request
     * @param  AdminAnnouncement $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, AdminAnnouncement $entity)
    {
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_announcement'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_announcement_index');
    }
}