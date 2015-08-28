<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\AdminBundle\Entity\AdminPost;
use Ojs\CmsBundle\Form\Type\PostType;
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
     */
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('VIEW', new AdminPost())) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $source = new Entity('OjsAdminBundle:AdminPost');
        $source->manipulateRow(
            function ($row) use ($request)
            {
                /**
                 * @var \APY\DataGridBundle\Grid\Row $row
                 * @var AdminPost $entity
                 */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());

                if(!is_null($entity)){
                    $row->setField('title', $entity->getTitle());
                    $row->setField('content', $entity->getContent());
                }

                return $row;
            }
        );

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
    public function newAction(Request $request)
    {
        $entity = new AdminPost();

        if (!$this->isGranted('CREATE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminPost:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a new Post entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new AdminPost())) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

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
     * Creates a form to create a Post entity.
     *
     * @param  AdminPost $entity The entity
     * @return Form The form
     */
    private function createCreateForm(AdminPost $entity)
    {
        $form = $this->createForm(new PostType($this->container), $entity,
            ['action' => $this->generateUrl('ojs_admin_post_create'), 'method' => 'POST']);
        $form->add('submit', 'submit', ['label' => 'Create']);
        return $form;
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

        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

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

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

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
     * Edits an existing Post entity.
     *
     * @param  Request $request
     * @param  AdminPost $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, AdminPost $entity)
    {
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('ojs_admin_post_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminPost:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
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
        $form = $this->createForm(new PostType($this->container), $entity,
            [
                'action' => $this->generateUrl('ojs_admin_post_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);
        return $form;
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

        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_post'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_post_index');
    }
}
