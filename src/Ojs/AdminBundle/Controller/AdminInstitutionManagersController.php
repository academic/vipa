<?php

namespace Ojs\AdminBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\AdminBundle\Form\Type\InstitutionManagersType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Ojs\AdminBundle\Entity\InstitutionManagers;

/**
 * InstitutionManagers controller.
 *
 */
class AdminInstitutionManagersController extends Controller
{
    /**
     * Lists all InstitutionManagers entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('OjsAdminBundle:InstitutionManagers');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_institution_managers_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_institution_managers_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_institution_managers_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminInstitutionManagers:index.html.twig', $data);
    }

    /**
     * Creates a new InstitutionManagers entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new InstitutionManagers())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionManagers();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_institution_managers_show', [
                'id' => $entity->getId()
                ]
            );
        }

        return $this->render(
            'OjsAdminBundle:AdminInstitutionManagers:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a InstitutionManagers entity.
     *
     * @param InstitutionManagers $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InstitutionManagers $entity)
    {
        $form = $this->createForm(
            new InstitutionManagersType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_institution_managers_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InstitutionManagers entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new InstitutionManagers())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionManagers();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminInstitutionManagers:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a InstitutionManagers entity.
     *
     * @param InstitutionManagers $entity
     * @return Response
     */
    public function showAction(InstitutionManagers $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity))
            throw new AccessDeniedException("You are not authorized for this page!");

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_institution_managers'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminInstitutionManagers:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing InstitutionManagers entity.
     *
     * @param InstitutionManagers $entity
     * @return Response
     */
    public function editAction(InstitutionManagers $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminInstitutionManagers:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a InstitutionManagers entity.
     *
     * @param InstitutionManagers $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(InstitutionManagers $entity)
    {
        $form = $this->createForm(
            new InstitutionManagersType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_institution_managers_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing InstitutionManagers entity.
     *
     * @param Request $request
     * @param InstitutionManagers $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, InstitutionManagers $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_institution_managers_edit', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminInstitutionManagers:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  InstitutionManagers                                   $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, InstitutionManagers $entity)
    {
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_institution_managers'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_institution_managers_index');
    }
}
