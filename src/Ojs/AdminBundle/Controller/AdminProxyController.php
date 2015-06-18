<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\Proxy;
use Ojs\UserBundle\Entity\User;
use Ojs\AdminBundle\Form\Type\ProxyType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Proxy controller.
 *
 */
class AdminProxyController extends Controller
{
    /**
     * Lists all Proxy entities.
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new Proxy()))
            throw new AccessDeniedException("You are not authorized for this page!");

        $source = new Entity('OjsUserBundle:Proxy');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");

        $rowAction[] = $gridAction->showAction('ojs_admin_proxy_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_proxy_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_proxy_delete', 'id');
        $actionColumn->setRowActions($rowAction);

        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsAdminBundle:AdminProxy:index.html.twig', ['grid' => $grid]);
    }

    /**
     * List child proxies
     *
     * @param  int $userId
     * @return Response
     */
    public function proxyChildrenAction($userId = null)
    {
        if (!$userId)
            $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:Proxy')->findBy(['proxyUserId' => $userId]);
        return $this->render('OjsAdminBundle:AdminProxy:clients.html.twig',['entities' => $entities]);
    }

    /**
     * List parent proxies
     *
     * @param  int $userId
     * @return Response
     */
    public function proxyParentsAction($userId = null)
    {
        if (!$userId)
            $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:Proxy')->findBy(['clientUserId' => $userId]);
        return $this->render('OjsAdminBundle:AdminProxy:parents.html.twig', ['entities' => $entities]);
    }

    /**
     * Creates a new Proxy entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Proxy()))
            throw new AccessDeniedException("You are not authorized for this page!");

        $entity = new Proxy();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('ojs_admin_proxy_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminProxy:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Proxy entity.
     *
     * @param Proxy $entity The entity
     * @return Form The form
     */
    private function createCreateForm(Proxy $entity)
    {
        $form = $this->createForm(
            new ProxyType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_proxy_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Proxy entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new Proxy()))
            throw new AccessDeniedException("You are not authorized for this page!");

        $entity = new Proxy();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminProxy:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Proxy entity.
     *
     * @param  Proxy    $entity
     * @return Response
     */
    public function showAction(Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity))
            throw new AccessDeniedException("You are not authorized for this page!");


        return $this->render(
            'OjsAdminBundle:AdminProxy:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Proxy entity.
     *
     * @param  Proxy    $entity
     * @return Response
     */
    public function editAction(Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity))
            throw new AccessDeniedException("You are not authorized for this page!");

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminProxy:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Proxy entity.
     *
     * @param Proxy $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Proxy $entity)
    {
        $form = $this->createForm(
            new ProxyType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_proxy_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Proxy entity.
     *
     * @param  Request                   $request
     * @param  Proxy                     $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity))
            throw new AccessDeniedException("You are not authorized for this page!");

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_proxy_edit', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminProxy:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Proxy entity.
     *
     * @param  Proxy            $entity
     * @return RedirectResponse
     */
    public function deleteAction(Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $entity))
            throw new AccessDeniedException("You are not authorized for this page!");

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_admin_proxy_index'));
    }
}
