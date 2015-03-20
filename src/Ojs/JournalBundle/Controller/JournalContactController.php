<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalContact;
use Ojs\JournalBundle\Form\JournalContactType;
use Ojs\Common\Helper\CommonFormHelper as CommonFormHelper;

/**
 * JournalContact controller.
 * 
 */
class JournalContactController extends Controller {

    /**
     * Lists all JournalContact entities.
     * @param \Ojs\JournalBundle\Entity\Journal $journal  if not set list all contacts. if set list only contacts for that journal
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($journal = null)
    {
        $source = new Entity('OjsJournalBundle:Article');
        if ($journal) { 
            $tableAlias = $source->getTableAlias();
            $source->manipulateQuery(
                    function ($query) use ($tableAlias, $journal) {
                $query->andWhere($tableAlias . '.journalId = ' . $journal->getId());
            }
            );
        }

        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('journalcontact_show', 'id');
        $rowAction[] = ActionHelper::editAction('journalcontact_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('journalcontact_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $this->render('OjsJournalBundle:JournalContact:index.html.twig', array(
                    'grid' => $grid,
        ));
    }

    /**
     * List all contacts for current journal
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexManagerAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        return $this->indexAction($journal);
    }

    /**
     * Creates a new JournalContact entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('journalcontact_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:JournalContact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalContact entity.
     *
     * @param JournalContact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalContact $entity)
    {
        $form = $this->createForm(new JournalContactType(), $entity, array(
            'action' => $this->generateUrl('journalcontact_create'),
            'method' => 'POST',
            'user' => $this->getUser()
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalContact entity.
     *
     */
    public function newAction()
    {
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:JournalContact:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalContact entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalContact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:JournalContact:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing JournalContact entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalContact entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:JournalContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalContact entity.
     *
     * @param JournalContact $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalContact $entity)
    {
        $form = $this->createForm(new JournalContactType(), $entity, array(
            'action' => $this->generateUrl('journalcontact_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser()
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalContact entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalContact entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('journalcontact_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:JournalContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a JournalContact entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find JournalContact entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('journalcontact'));
    }

    /**
     * Creates a form to delete a JournalContact entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $formHelper = new CommonFormHelper();

        return $formHelper->createDeleteForm($this, $id, 'journalcontact_delete');
    }

}
