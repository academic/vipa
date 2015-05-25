<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Form\RoleType;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class RoleController extends Controller
{
    /**
     * Lists all Role entities.
     */
    public function indexAction()
    {
        $source = new Entity('OjsUserBundle:Role');
        $grid = $this->get('grid');
        $grid->setSource($source);

        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        ActionHelper::setup($this->get('security.csrf.token_manager'));

        $rowAction[] = ActionHelper::showAction('role_show', 'id');
        $rowAction[] = ActionHelper::editAction('role_edit', 'id');
        $rowAction[] = ActionHelper::editAction('role_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsUserBundle:Role:index.html.twig', $data);
    }

    /**
     * Creates a new Role entity.
     *
     * @param  Request                $request
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Role();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('role_show', ['id' => $entity->getId()]);
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Role entity.
     *
     * @param  Role $entity
     * @return Form
     */
    private function createCreateForm(Role $entity)
    {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Role entity.
     */
    public function newAction()
    {
        $entity = new Role();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsUserBundle:Role:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Role entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }

        return $this->render('OjsUserBundle:Role:show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsUserBundle:Role:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Role $entity)
    {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('attr' => array('label ' => $this->get('translator')->trans('Update')),
        ));

        return $form;
    }

    /**
     * Edits an existing Role entity.
     *
     * @param  Request                $request
     * @param $id
     * @return array|RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Role $entity */
        $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('role_edit', ['id' => $id]);
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Role entity.
     * @param  Role             $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Role $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('role'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('role');
    }
}
