<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Form\RoleType;
use Ojs\Common\Controller\OjsController as Controller;

class RoleController extends Controller
{
    /**
     * Lists all Role entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $source = new Entity('OjsUserBundle:Role');
        $grid = $this->get('grid');
        $grid->setSource($source);

        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        $rowAction[] = ActionHelper::showAction('role_show', 'id');
        $rowAction[] = ActionHelper::editAction('role_edit', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsUserBundle:Role:index.html.twig', $data);
        $entities = $em->getRepository('OjsUserBundle:Role')->findAll();

        return $this->render('OjsUserBundle:Role:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Role entity.
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
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsUserBundle:Role:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Role entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsUserBundle:Role:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Role $entity)
    {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('attr' => array('label ' =>
            $this->get('translator')->trans('Update'))
        ));

        return $form;
    }

    /**
     * Edits an existing Role entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
        }
        $deleteForm = $this->createDeleteForm($id);
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
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Role entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsUserBundle:Role')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('Not Found'));
            }
            $em->remove($entity);
            $em->flush();
        }
        $this->successFlashBag('successful.remove');
        return $this->redirectToRoute('role');
    }

    /**
     * Creates a form to delete a Role entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $t = $this->get('translator');

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('role_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' =>
                $t->trans('Delete'),
                'attr' => array(
                    'class' => 'button alert',
                    'onclick' => 'return confirm("' . $t->trans('Are you sure?') . '"); ')
            ))
            ->getForm();
    }

}
