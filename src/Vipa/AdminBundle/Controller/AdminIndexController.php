<?php

namespace Vipa\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\AdminBundle\Form\Type\IndexType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Index;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * AdminIndexController controller.
 *
 */
class AdminIndexController extends Controller
{

    /**
     * Lists all Index entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('VipaJournalBundle:Index');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_admin_index_show', 'id');
        $rowAction[] = $gridAction->editAction('vipa_admin_index_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('vipa_admin_index_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('VipaAdminBundle:AdminIndex:index.html.twig', $data);
    }

    /**
     * Creates a new Index entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Index();
        $form = $this->createCreateForm($entity)
            ->add('create', 'submit', array('label' => 'c'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('vipa_admin_index_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'VipaAdminBundle:AdminIndex:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Index entity.
     *
     * @param Index $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Index $entity)
    {
        $form = $this->createForm(
            new IndexType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_index_create'),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Index entity.
     *
     */
    public function newAction()
    {
        $entity = new Index();
        $form = $this->createCreateForm($entity)
            ->add('create', 'submit', array('label' => 'c'));

        return $this->render(
            'VipaAdminBundle:AdminIndex:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Index entity.
     *
     * @param  Index $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Index $entity)
    {
        $this->throw404IfNotFound($entity);
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_admin_index'.$entity->getId());

        return $this->render(
            'VipaAdminBundle:AdminIndex:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Index entity.
     *
     * @param  Index $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Index $entity)
    {
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity)
            ->add('save', 'submit');

        return $this->render(
            'VipaAdminBundle:AdminIndex:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Index entity.
     *
     * @param Index $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Index $entity)
    {
        $form = $this->createForm(
            new IndexType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_admin_index_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Index entity.
     *
     * @param  Request $request
     * @param  Index $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Index $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity)
            ->add('save', 'submit');
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('vipa_admin_index_edit', array('id' => $entity->getId())));
        }

        return $this->render(
            'VipaAdminBundle:AdminIndex:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Index entity.
     *
     * @param  Request $request
     * @param  Index $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Index $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_admin_index'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();

        $this->successFlashBag('successful.remove');
        return $this->redirectToRoute('vipa_admin_index_index');
    }
}
