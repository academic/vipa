<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojs\JournalBundle\Entity\JournalsIndex;
use Ojs\JournalBundle\Form\JournalsIndexType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * JournalsIndex controller.
 *
 */
class JournalsIndexController extends Controller
{

    /**
     * Lists all JournalsIndex entities.
     *
     */
    public function indexAction($journal = null)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal()->getId();
        $source = new Entity('OjsJournalBundle:JournalsIndex');
        if ($journal) {
            $ta = $source->getTableAlias();
            $source->manipulateQuery(function (QueryBuilder $qb) use ($journal, $ta) {
                $qb->andWhere(
                    $qb->expr()->eq("$ta.journal_id", ':journal')
                )
                    ->setParameter('journal', $journal);
            });
        }
        if (!$journal)
            throw new NotFoundHttpException("Journal not found!");
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('manager_journals_indexes_show', 'id');
        $rowAction[] = ActionHelper::editAction('manager_journals_indexes_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('manager_journals_indexes_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['journal_id'] = $journal;
        return $grid->getGridResponse('OjsJournalBundle:JournalsIndex:index.html.twig', $data);
    }

    /**
     * Creates a new JournalsIndex entity.
     *
     */
    public function createAction(Request $request, $journal = null)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal()->getId();
        $em = $this->getDoctrine()->getManager();
        $entity = new JournalsIndex();
        if ($journal) {
            $journalObj = $em->find('OjsJournalBundle:Journal', $journal);
            $entity->setJournalId($journal);
            $entity->setJournal($journalObj);
        }
        if (!$journal)
            throw new NotFoundHttpException("Journal not found!");
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournalIndexId($entity->getJournalIndex()->getId());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('manager_journals_indexes_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:JournalsIndex:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalsIndex entity.
     *
     * @param JournalsIndex $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalsIndex $entity)
    {
        $form = $this->createForm(new JournalsIndexType(), $entity, array(
            'action' => $this->generateUrl('manager_journals_indexes_create', ['journal' => $entity->getJournalId()]),
            'method' => 'POST',
            'user' => $this->getUser()
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalsIndex entity.
     * @param $journal integer
     * @return Response
     */
    public function newAction($journal = null)
    {
        $entity = new JournalsIndex();
        $journal = $this->get("ojs.journal_service")->getSelectedJournal()->getId();
        if ($journal) {
            $entity->setJournalId($journal);
        }
        if(!$journal)
            throw new NotFoundHttpException('Journal not found!');
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:JournalsIndex:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalsIndex entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalsIndex')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalsIndex entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:JournalsIndex:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing JournalsIndex entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalsIndex')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalsIndex entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:JournalsIndex:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalsIndex entity.
     *
     * @param JournalsIndex $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalsIndex $entity)
    {
        $form = $this->createForm(new JournalsIndexType(), $entity, array(
            'action' => $this->generateUrl('manager_journals_indexes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser()
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalsIndex entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalsIndex')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalsIndex entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('manager_journals_indexes_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:JournalsIndex:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a JournalsIndex entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:JournalsIndex')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find JournalsIndex entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('manager_journals_indexes'));
    }

    /**
     * Creates a form to delete a JournalsIndex entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('manager_journals_indexes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
