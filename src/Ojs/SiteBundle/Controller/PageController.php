<?php

namespace Ojs\SiteBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\SiteBundle\Entity\Page;
use Ojs\SiteBundle\Form\Type\PageType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page controller.
 *
 */
class PageController extends Controller
{

    /**
     * Lists all Page entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsSiteBundle:Page');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        $rowAction[] = $gridAction->showAction('admin_page_show', 'id');
        $rowAction[] = $gridAction->editAction('admin_page_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('admin_page_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsSiteBundle:Page:index.html.twig', $data);
    }

    /**
     * Creates a new Page entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new Page();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('admin_page_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsSiteBundle:Page:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Page entity.
     *
     * @param Page $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Page $entity)
    {
        $form = $this->createForm(
            new PageType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_page_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     */
    public function newAction()
    {
        $entity = new Page();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsSiteBundle:Page:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsSiteBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render(
            'OjsSiteBundle:Page:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Page $entity */
        $entity = $em->getRepository('OjsSiteBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsSiteBundle:Page:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Page entity.
     *
     * @param Page $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Page $entity)
    {
        $form = $this->createForm(
            new PageType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_page_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Page entity.
     *
     * @param  Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Page $entity */
        $entity = $em->getRepository('OjsSiteBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('admin_page_edit', array('id' => $id)));
        }

        return $this->render(
            'OjsSiteBundle:Page:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Page entity.
     * @param  Page $entity
     * @return RedirectResponse
     */
    public function deleteAction(Page $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('admin_page');
    }
}
