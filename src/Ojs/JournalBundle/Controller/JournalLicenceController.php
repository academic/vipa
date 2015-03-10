<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojs\JournalBundle\Entity\JournalLicence;
use Ojs\JournalBundle\Form\JournalLicenceType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * JournalLicence controller.
 *
 */
class JournalLicenceController extends Controller
{

    /**
     * Lists all JournalLicence entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal()->getId();
        $source = new Entity('OjsJournalBundle:JournalLicence');
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
        $rowAction[] = ActionHelper::showAction('manager_journal_licence_show', 'id');
        $rowAction[] = ActionHelper::editAction('manager_journal_licence_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('manager_journal_licence_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        $data['journal_id'] = $journal;
        return $grid->getGridResponse('OjsJournalBundle:JournalLicence:index.html.twig', $data);
    }

    /**
     * Creates a new JournalLicence entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new JournalLicence();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
            $entity->setJournal($journal);
            $entity->setJournalId($journal->getId());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('manager_journal_licence_show', array('id' => $entity->getId())));
        }

        return $this->render('OjsJournalBundle:JournalLicence:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalLicence entity.
     *
     * @param JournalLicence $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalLicence $entity)
    {
        $form = $this->createForm(new JournalLicenceType(), $entity, array(
            'action' => $this->generateUrl('manager_journal_licence_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalLicence entity.
     *
     */
    public function newAction()
    {
        $entity = new JournalLicence();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:JournalLicence:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JournalLicence entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalLicence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalLicence entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:JournalLicence:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing JournalLicence entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalLicence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalLicence entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:JournalLicence:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a JournalLicence entity.
     *
     * @param JournalLicence $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalLicence $entity)
    {
        $form = $this->createForm(new JournalLicenceType(), $entity, array(
            'action' => $this->generateUrl('manager_journal_licence_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalLicence entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalLicence')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find JournalLicence entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('manager_journal_licence_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:JournalLicence:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a JournalLicence entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OjsJournalBundle:JournalLicence')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find JournalLicence entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('manager_journal_licence'));
    }

    /**
     * Creates a form to delete a JournalLicence entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('manager_journal_licence_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
