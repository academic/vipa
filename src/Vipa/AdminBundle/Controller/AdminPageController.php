<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\AdminBundle\Entity\AdminPage;
use Vipa\AdminBundle\Form\Type\AdminPageType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * Page controller.
 */
class AdminPageController extends Controller
{

    /**
     * List all page entities
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('VipaAdminBundle:AdminPage');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('vipa_admin_page_show', 'id');
        $rowAction[] = $gridAction->editAction('vipa_admin_page_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('vipa_admin_page_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('VipaAdminBundle:AdminPage:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Displays a form to create a new Page entity.
     */
    public function newAction()
    {
        $entity = new AdminPage();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaAdminBundle:AdminPage:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a Page entity.
     *
     * @param  AdminPage $entity The entity
     * @return Form The form
     */
    private function createCreateForm(AdminPage $entity)
    {
        $form = $this->createForm(
            new AdminPageType(),
            $entity,
            ['action' => $this->generateUrl('vipa_admin_page_create'), 'method' => 'POST']
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new Page entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new AdminPage();
        $entity->setCurrentLocale($request->getDefaultLocale());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setSlug($entity->getTranslationByLocale($request->getDefaultLocale())->getTitle());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('vipa_admin_page_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminPage:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @param  AdminPage $entity
     * @return Response
     */
    public function showAction(AdminPage $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_page'.$entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminPage:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @param  AdminPage $entity
     * @return Response
     */
    public function editAction(AdminPage $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_page'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaAdminBundle:AdminPage:edit.html.twig',
            [
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to edit a Page entity.
     *
     * @param  AdminPage $entity The entity
     * @return Form The form
     */
    private function createEditForm(AdminPage $entity)
    {
        $form = $this->createForm(
            new AdminPageType(),
            $entity,
            [
                'action' => $this->generateUrl('vipa_admin_page_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Page entity.
     *
     * @param  Request $request
     * @param  AdminPage $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, AdminPage $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_admin_page_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminPage:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Page entity.
     * @param  Request $request
     * @param  AdminPage $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, AdminPage $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_admin_page'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_admin_page_index');
    }
}
