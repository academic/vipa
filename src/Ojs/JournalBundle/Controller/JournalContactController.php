<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalContact;
use Ojs\JournalBundle\Form\JournalContactType;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * JournalContact controller.
 * 
 */
class JournalContactController extends Controller {

    /**
     * @Inject("security.context", required = false)
     */
    private $securityContext;
    
    /**
     * @Inject("user.helper", required = false)
     */
    private $userHelper;

    /**
     * Lists all JournalContact entities.
     * @param \Ojs\JournalBundle\Entity\Journal $journal  if not set list all contacts. if set list only contacts for that journal
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($journal = null)
    {
        $source = new Entity('OjsJournalBundle:JournalContact');
        if ($journal) {
            $tableAlias = $source->getTableAlias();
            $source->manipulateQuery(
                    function ($query) use ($tableAlias, $journal) {
                $query->andWhere($tableAlias . '.journalId = ' . $journal->getId());
            }
            );
        }

        $grid = $this->get('grid');
        $grid->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction = [];
        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $rowAction[] = ActionHelper::showAction('journalcontact_show', 'id');
            $rowAction[] = ActionHelper::editAction('journalcontact_edit', 'id');
            $rowAction[] = ActionHelper::deleteAction('journalcontact_delete', 'id');
        } else if ($this->userHelper->hasJournalRole('ROLE_JOURNAL_MANAGER')) {
            $rowAction[] = ActionHelper::showAction('manager_journalcontact_show', 'id');
            $rowAction[] = ActionHelper::editAction('manager_journalcontact_edit', 'id');
            $rowAction[] = ActionHelper::deleteAction('manager_journalcontact_delete', 'id');
        }
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:JournalContact:index.html.twig', array(
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
        $isAdmin =$this->securityContext->isGranted('ROLE_SUPER_ADMIN'); 
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('Successfully created');
            return $this->redirectToRoute($isAdmin ? 'journalcontact_show' : 'manager_journalcontact_show', [
                'id' => $entity->getId()
                ]
            );
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
     * @param array $optionsArray
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalContact $entity, $optionsArray = array())
    {
        $isAdmin = $this->securityContext->isGranted('ROLE_SUPER_ADMIN');
        $options = array_merge(array(
            'action' => $this->generateUrl($isAdmin ? 'journalcontact_create' : 'manager_journalcontact_create'),
            'method' => 'POST',
            'user' => $this->getUser()
                ), $optionsArray);
        $form = $this->createForm(new JournalContactType(), $entity, $options);

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function newManagerAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        return $this->newAction($journal);
    }

    /**
     * Displays a form to create a new JournalContact entity.
     * @param Journal $journal
     */
    public function newAction($journal = null)
    {
        $options['user'] = $this->getUser();
        $options['journal'] = $journal? : null;
        $entity = new JournalContact();
        $form = $this->createCreateForm($entity, $options);

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
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:JournalContact:show.html.twig', array(
                    'entity' => $entity));
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
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:JournalContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
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
        $isAdmin =  $this->securityContext->isGranted('ROLE_SUPER_ADMIN');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('Successfully updated');
            return $this->redirectToRoute($isAdmin ? 'journalcontact_edit' : 'manager_journalcontact_edit', [
                'id' => $id
                ]
            );
        }

        return $this->render('OjsJournalBundle:JournalContact:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a JournalContact entity.
     *
     */
    public function deleteAction($id)
    {
        $isAdmin =  $this->securityContext->isGranted('ROLE_SUPER_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:JournalContact')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('Successfully removed');
        return $this->redirectToRoute($isAdmin ? 'journalcontact' : 'manager_journalcontact');
    }

}
