<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Entity\AdminJournalTheme;
use Ojs\AdminBundle\Form\Type\AdminJournalThemeType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * AdminJournalTheme controller.
 *
 */
class AdminJournalThemeController extends Controller
{
    /**
     * Lists all Themes entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsAdminBundle:AdminJournalTheme');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_journal_theme_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_journal_theme_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_journal_theme_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminJournalTheme:index.html.twig', $data);
    }

    /**
     * Creates a new Theme entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new AdminJournalTheme();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_journal_theme_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminJournalTheme:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a PublisherTypes entity.
     *
     * @param Theme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminJournalTheme $entity)
    {
        $form = $this->createForm(
            new AdminJournalThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_journal_theme_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Theme entity.
     *
     */
    public function newAction()
    {
        $entity = new AdminJournalTheme();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminJournalTheme:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsAdminBundle:AdminJournalTheme')->find($id);
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_journal_theme' . $entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminJournalTheme:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var AdminJournalTheme $entity */
        $entity = $em->getRepository('OjsAdminBundle:AdminJournalTheme')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminJournalTheme:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a PublisherTypes entity.
     *
     * @param AdminJournalTheme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(AdminJournalTheme $entity)
    {
        $form = $this->createForm(
            new AdminJournalThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_journal_theme_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Themes entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var AdminJournalTheme $entity */
        $entity = $em->getRepository('OjsAdminBundle:AdminJournalTheme')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_journal_theme_edit', ['id' => $id]);
        }

        return $this->render(
            'OjsAdminBundle:AdminJournalTheme:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  AdminJournalTheme $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, AdminJournalTheme $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_journal_theme' . $entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_journal_theme_index');
    }
}
