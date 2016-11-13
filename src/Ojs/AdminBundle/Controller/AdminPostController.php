<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Entity\AdminPost;
use Ojs\AdminBundle\Form\Type\AdminPostType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * Post controller.
 */
class AdminPostController extends Controller
{

    /**
     * Lists all Post entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('OjsAdminBundle:AdminPost');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_admin_post_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_post_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_post_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsAdminBundle:AdminPost:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Displays a form to create a new Post entity.
     */
    public function newAction()
    {
        $entity = new AdminPost();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminPost:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a Post entity.
     *
     * @param  AdminPost $entity The entity
     * @return Form The form
     */
    private function createCreateForm(AdminPost $entity)
    {
        $form = $this->createForm(
            new AdminPostType(),
            $entity,
            ['action' => $this->generateUrl('ojs_admin_post_create'), 'method' => 'POST']
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new Post entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new AdminPost();
        $entity->setCurrentLocale($request->getDefaultLocale());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setSlug($entity->getTranslationByLocale($request->getDefaultLocale())->getTitle());
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_post_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminPost:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a Post entity.
     *
     * @param  AdminPost $entity
     * @return Response
     */
    public function showAction(AdminPost $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_post'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminPost:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @param  AdminPost $entity
     * @return Response
     */
    public function editAction(AdminPost $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_post'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminPost:edit.html.twig',
            [
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to edit a Post entity.
     *
     * @param  AdminPost $entity The entity
     * @return Form The form
     */
    private function createEditForm(AdminPost $entity)
    {
        $form = $this->createForm(
            new AdminPostType(),
            $entity,
            [
                'action' => $this->generateUrl('ojs_admin_post_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Post entity.
     *
     * @param  Request $request
     * @param  AdminPost $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, AdminPost $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_post_edit', ['id' => $entity->getId()]);
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_post'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminPost:edit.html.twig',
            array(
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Post entity.
     * @param  Request $request
     * @param  AdminPost $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, AdminPost $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_post'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_post_index');
    }
}
