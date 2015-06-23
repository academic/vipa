<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Design;
use Ojs\AdminBundle\Form\Type\DesignType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Design controller.
 *
 */
class AdminDesignController extends Controller
{
    /**
     * Lists all Design entities.
     *
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new Design())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:Design');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_design_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_design_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_design_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminDesign:index.html.twig', $data);
    }

    /**
     * Creates a new Design entity.
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Design())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Design();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_design_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminDesign:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Design entity.
     *
     * @param Design $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Design $entity)
    {
        $form = $this->createForm(
            new DesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_design_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Design entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new Design())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Design();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminDesign:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Design entity.
     * @param  Design                                     $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Design $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', new Design()))
            throw new AccessDeniedException("You are not authorized for this page!");

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_design'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminDesign:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Design entity.
     * @param  Design                                     $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Design $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', new Design())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Design entity.
     *
     * @param Design $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Design $entity)
    {
        $form = $this->createForm(
            new DesignType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_design_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Design entity.
     * @param  Request                                                                                       $request
     * @param  Design                                                                                        $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Design $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', new Design())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_design_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminDesign:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  Design                                             $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, Design $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', new Design())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_design'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_design_index');
    }
}
