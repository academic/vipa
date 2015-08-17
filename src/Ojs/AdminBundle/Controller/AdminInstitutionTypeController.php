<?php

namespace Ojs\AdminBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\AdminBundle\Form\Type\InstitutionTypesType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * InstitutionTypes controller.
 *
 */
class AdminInstitutionTypeController extends Controller
{
    /**
     * Lists all InstitutionTypes entities.
     *
     */
    public function indexAction(Request $request)
    {
        if (!$this->isGranted('VIEW', new InstitutionTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:InstitutionTypes');
        $source->manipulateRow(
            function ($row) use ($request)
            {
                /**
                 * @var \APY\DataGridBundle\Grid\Row $row
                 * @var InstitutionTypes $entity
                 */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if(!is_null($entity)){
                    $row->setField('name', $entity->getName());
                    $row->setField('description', $entity->getDescription());
                }
                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_institution_type_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_institution_type_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_institution_type_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminInstitutionType:index.html.twig', $data);
    }

    /**
     * Creates a new InstitutionTypes entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new InstitutionTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setTranslatableLocale($request->getDefaultLocale());
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_admin_institution_type_show', ['id' => $entity->getId()]);
        }

        return $this->render(
            'OjsAdminBundle:AdminInstitutionType:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a InstitutionTypes entity.
     *
     * @param InstitutionTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InstitutionTypes $entity)
    {
        $form = $this->createForm(
            new InstitutionTypesType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_institution_type_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InstitutionTypes entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new InstitutionTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminInstitutionType:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a InstitutionTypes entity.
     *
     * @param Request $request
     * @param InstitutionTypes $entity
     * @return Response
     */
    public function showAction(Request $request, InstitutionTypes $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity))
            throw new AccessDeniedException("You are not authorized for this page!");

        $entity->setDefaultLocale($request->getDefaultLocale());
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_institution_type'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminInstitutionType:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing InstitutionTypes entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var InstitutionTypes $entity */
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminInstitutionType:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a InstitutionTypes entity.
     *
     * @param InstitutionTypes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(InstitutionTypes $entity)
    {
        $form = $this->createForm(
            new InstitutionTypesType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_institution_type_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing InstitutionTypes entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var InstitutionTypes $entity */
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_admin_institution_type_edit', ['id' => $id]);
        }

        return $this->render(
            'OjsAdminBundle:AdminInstitutionType:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request                                            $request
     * @param  InstitutionTypes                                   $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws TokenNotFoundException
     */
    public function deleteAction(Request $request, InstitutionTypes $entity)
    {
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_institution_type'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_admin_institution_type_index');
    }
}
