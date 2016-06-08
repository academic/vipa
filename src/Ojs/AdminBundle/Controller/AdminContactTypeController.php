<?php

namespace Ojs\AdminBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\AdminBundle\Form\Type\ContactTypesType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\ContactTypes;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * ContactTypes controller.
 *
 */
class AdminContactTypeController extends Controller
{
    /**
     * Lists all ContactTypes entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('OjsJournalBundle:ContactTypes');
        $source->manipulateRow(
            function (Row $row) use ($request) {

                /* @var ContactTypes $entity */
                $entity = $row->getEntity();
                $entity->setDefaultLocale($request->getDefaultLocale());
                if (!is_null($entity)) {
                    $row->setField('translations.name', $entity->getNameTranslations());
                    $row->setField('translations.description', $entity->getDescriptionTranslations());
                }

                return $row;
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_admin_contact_type_show', 'id');
        $rowAction[] = $gridAction->editAction('ojs_admin_contact_type_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('ojs_admin_contact_type_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsAdminBundle:AdminContactType:index.html.twig', $data);
    }

    /**
     * Creates a new ContactTypes entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $entity = new ContactTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('ojs_admin_contact_type_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsAdminBundle:AdminContactType:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a ContactTypes entity.
     *
     * @param ContactTypes $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(ContactTypes $entity)
    {
        $form = $this->createForm(
            new ContactTypesType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_contact_type_create'),
                'method' => 'POST',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ContactTypes entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new ContactTypes();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminContactType:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a ContactTypes entity.
     *
     * @param Request $request
     * @param ContactTypes $entity
     * @return Response
     */
    public function showAction(Request $request, ContactTypes $entity)
    {
        $entity->setDefaultLocale($request->getDefaultLocale());
        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_admin_contact_type'.$entity->getId());

        return $this->render(
            'OjsAdminBundle:AdminContactType:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing ContactTypes entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsAdminBundle:AdminContactType:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a ContactTypes entity.
     *
     * @param ContactTypes $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(ContactTypes $entity)
    {
        $form = $this->createForm(
            new ContactTypesType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_admin_contact_type_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing ContactTypes entity.
     *
     * @param  Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('ojs_admin_contact_type_edit', array('id' => $id)));
        }

        return $this->render(
            'OjsAdminBundle:AdminContactType:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a ContactTypes entity.
     *
     * @param  Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_admin_contact_type'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('ojs_admin_contact_type_index'));
    }
}
