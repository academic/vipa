<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Ojs\AdminBundle\Form\Type\JournalIndexType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalIndex;
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
     * Lists all JournalIndex entities.
     *
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new JournalIndex())) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $source = new Entity('OjsJournalBundle:JournalIndex');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_index_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_index_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_index_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminIndex:index.html.twig', $data);
    }

    /**
     * Creates a new JournalIndex entity.
     *
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new JournalIndex())) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalIndex();
        $form = $this->createCreateForm($entity)
            ->add('create', 'submit', array('label' => 'c'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_index_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminIndex:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a JournalIndex entity.
     *
     * @param JournalIndex $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalIndex $entity)
    {
        $form = $this->createForm(
            new JournalIndexType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_index_create'),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new JournalIndex entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new JournalIndex())) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalIndex();
        $form = $this->createCreateForm($entity)
            ->add('create', 'submit', array('label' => 'c'));

        return $this->render(
            'OjsAdminBundle:AdminIndex:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalIndex entity.
     *
     * @param  JournalIndex                               $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JournalIndex $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity))
            throw new AccessDeniedException("You are not authorized for view this page!");

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_index'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminIndex:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing JournalIndex entity.
     *
     * @param  JournalIndex                               $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(JournalIndex $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity)
            ->add('save', 'submit');

        return $this->render(
            'OjsAdminBundle:AdminIndex:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a JournalIndex entity.
     *
     * @param JournalIndex $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalIndex $entity)
    {
        $form = $this->createForm(
            new JournalIndexType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_index_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing JournalIndex entity.
     *
     * @param  Request                                                                                       $request
     * @param  JournalIndex                                                                                  $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, JournalIndex $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity)
            ->add('save', 'submit');
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_index_edit', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminIndex:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a JournalIndex entity.
     *
     * @param  Request                                            $request
     * @param  JournalIndex                                       $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, JournalIndex $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_index'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_index_index');
    }
}
