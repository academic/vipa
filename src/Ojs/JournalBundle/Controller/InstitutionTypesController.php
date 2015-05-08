<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\JournalBundle\Form\InstitutionTypesType;
use Ojs\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * InstitutionTypes controller.
 *
 */
class InstitutionTypesController extends Controller
{
    /**
     * Lists all InstitutionTypes entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:InstitutionTypes');
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('institution_types_show', 'id');
        $rowAction[] = ActionHelper::editAction('institution_types_edit', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:InstitutionTypes:index.html.twig',$data);
    }

    /**
     * Creates a new InstitutionTypes entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('institution_types_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:InstitutionTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
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
        $form = $this->createForm(new InstitutionTypesType(), $entity, array(
            'action' => $this->generateUrl('institution_types_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InstitutionTypes entity.
     *
     */
    public function newAction()
    {
        $entity = new InstitutionTypes();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:InstitutionTypes:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InstitutionTypes entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:InstitutionTypes:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing InstitutionTypes entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:InstitutionTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
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
        $form = $this->createForm(new InstitutionTypesType(), $entity, array(
            'action' => $this->generateUrl('institution_types_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing InstitutionTypes entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
        $this->throw404IfNotFound($entity);
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('institution_types_edit', ['id' => $id]);
        }

        return $this->render('OjsJournalBundle:InstitutionTypes:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a InstitutionTypes entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:InstitutionTypes')->find($id);
            $this->throw404IfNotFound($entity);
            $em->remove($entity);
            $em->flush();
        }
        $this->successFlashBag('successful.remove');
        return $this->redirectToRoute('institution_types');
    }

    /**
     * Creates a form to delete a InstitutionTypes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $formHelper = new CommonFormHelper();

        return $formHelper->createDeleteForm($this, $id,'institution_types_delete');
    }

}
