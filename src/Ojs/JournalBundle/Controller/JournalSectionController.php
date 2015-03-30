<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Entity\JournalSection;
use Ojs\JournalBundle\Form\JournalSectionType;

/**
 * JournalSection controller.
 *
 */
class JournalSectionController extends Controller {

    /**
     * Lists all JournalSection entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:JournalSection');
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
         $ta = $source->getTableAlias();
        $source->manipulateQuery(function (\Doctrine\ORM\QueryBuilder $qb) use ($journal,$ta) {
            $qb->where(
                    $qb->expr()->eq($ta.'.journalId', $journal->getId())
            );
            return $qb;
        });
        $source->manipulateRow(function(Row $row) {
            if ($row->getField("title") and strlen($row->getField('title')) > 20) {
                $row->setField('title', substr($row->getField('title'), 0, 20) . "...");
            }
            return $row;
        });
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('manager_journal_section_show', 'id');
        $rowAction[] = ActionHelper::editAction('manager_journal_section_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('manager_journal_section_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:JournalSection:index.html.twig', $data);
    }

    /**
     * Creates a new JournalSection entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new JournalSection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('manager_journal_section_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:JournalSection:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalSection entity.
     * @param  JournalSection               $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalSection $entity)
    {
        $form = $this->createForm(new JournalSectionType(), $entity, array(
            'action' => $this->generateUrl('manager_journal_section_create'),
            'method' => 'POST',
            'user' => $this->getUser(),
            'journal' => $this->get('ojs.journal_service')->getSelectedJournal()
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalSection entity.
     *
     */
    public function newAction()
    {
        $entity = new JournalSection();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:JournalSection:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalSection entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }

        return $this->render('OjsJournalBundle:JournalSection:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing JournalSection entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:JournalSection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalSection entity.
     *
     * @param JournalSection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalSection $entity)
    {
        $form = $this->createForm(new JournalSectionType(), $entity, array(
            'action' => $this->generateUrl('manager_journal_section_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
            'journal' => $this->get('ojs.journal_service')->getSelectedJournal()
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalSection entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('manager_journal_section_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:JournalSection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a JournalSection entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalSection entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('manager_journal_section'));
    }

}
