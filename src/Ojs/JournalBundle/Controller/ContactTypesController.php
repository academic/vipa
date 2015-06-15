<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\ContactTypes;
use Ojs\JournalBundle\Form\Type\ContactTypesType;
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
class ContactTypesController extends Controller
{
    /**
     * Lists all ContactTypes entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new ContactTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:ContactTypes');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('contacttypes_show', 'id');
        $rowAction[] = $gridAction->editAction('contacttypes_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('contacttypes_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:ContactTypes:index.html.twig', $data);
    }

    /**
     * Creates a new ContactTypes entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new ContactTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new ContactTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('contacttypes_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsJournalBundle:ContactTypes:new.html.twig',
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
                'action' => $this->generateUrl('contacttypes_create'),
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
        if (!$this->isGranted('CREATE', new ContactTypes())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new ContactTypes();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:ContactTypes:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a ContactTypes entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        return $this->render(
            'OjsJournalBundle:ContactTypes:show.html.twig',
            array(
                'entity' => $entity,
            )
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
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:ContactTypes:edit.html.twig',
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
                'action' => $this->generateUrl('contacttypes_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing ContactTypes entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('contacttypes_edit', array('id' => $id)));
        }

        return $this->render(
            'OjsJournalBundle:ContactTypes:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a ContactTypes entity.
     *
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:ContactTypes')->find($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('contacttypes'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('contacttypes'));
    }
}
