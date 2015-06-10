<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Theme;
use Ojs\JournalBundle\Form\ThemeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Theme controller.
 *
 */
class ThemeController extends Controller
{
    /**
     * Lists all Theme entities.
     *
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new Theme())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:Theme');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('theme_show', 'id');
        $rowAction[] = $gridAction->editAction('theme_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('theme_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Theme:index.html.twig', $data);
    }

    /**
     * Creates a new Theme entity.
     *
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Theme())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Theme();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('theme_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:Theme:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Theme entity.
     *
     * @param Theme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Theme $entity)
    {
        $form = $this->createForm(
            new ThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('theme_create'),
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
        if (!$this->isGranted('CREATE', new Theme())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Theme();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:Theme:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @param  Theme                                      $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Theme $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        return $this->render(
            'OjsJournalBundle:Theme:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @param  Theme                                      $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Theme $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:Theme:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Theme entity.
     *
     * @param Theme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Theme $entity)
    {
        $form = $this->createForm(
            new ThemeType(),
            $entity,
            array(
                'action' => $this->generateUrl('theme_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Theme entity.
     *
     * @param  Request                                                                                       $request
     * @param  Theme                                                                                         $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Theme $entity)
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

            return $this->redirectToRoute('theme_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:Theme:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Theme entity.
     *
     * @param  Request                                            $request
     * @param  Theme                                              $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Theme $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('theme'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('theme');
    }
}
