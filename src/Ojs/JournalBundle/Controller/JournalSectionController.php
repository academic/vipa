<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Entity\JournalSection;
use Ojs\JournalBundle\Form\JournalSectionType;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * JournalSection controller.
 *
 */
class JournalSectionController extends Controller
{

    /**
     * Lists all JournalSection entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('VIEW', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for view this journal's sections!");
        }
        $source = new Entity('OjsJournalBundle:JournalSection');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function (QueryBuilder $qb) use ($journal, $ta) {
            $qb->where(
                    $qb->expr()->eq($ta.'.journalId', $journal->getId())
            );

            return $qb;
        });
        $source->manipulateRow(function (Row $row) {
            if ($row->getField("title") and strlen($row->getField('title')) > 20) {
                $row->setField('title', substr($row->getField('title'), 0, 20)."...");
            }

            return $row;
        });
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        
        $rowAction[] = $gridAction->showAction('manager_journal_section_show', 'id');
        if($this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'sections')) {
            $rowAction[] = $gridAction->editAction('manager_journal_section_edit', 'id');
        }
        if($this->isGranted('DELETE', $this->get('ojs.journal_service')->getSelectedJournal(), 'sections')) {
            $rowAction[] = $gridAction->deleteAction('manager_journal_section_delete', 'id');
        }

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:JournalSection:index.html.twig', $data);
    }

    /**
     * Creates a new JournalSection entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('CREATE', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for create section on this journal!");
        }
        $entity = new JournalSection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $entity->setJournal($journal);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('manager_journal_section_show', [
                'id' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:JournalSection:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a JournalSection entity.
     * @param  JournalSection $entity The entity
     * @return Form           The form
     */
    private function createCreateForm(JournalSection $entity)
    {
        $form = $this->createForm(new JournalSectionType(), $entity, array(
            'action' => $this->generateUrl('manager_journal_section_create'),
            'method' => 'POST',
            'user' => $this->getUser(),
            'journal' => $this->get('ojs.journal_service')->getSelectedJournal(),
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalSection entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('CREATE', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for create section on this journal!");
        }
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
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('VIEW', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for view this journal's section!");
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        return $this->render('OjsJournalBundle:JournalSection:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing JournalSection entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('EDIT', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's section!");
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
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
     * @return Form The form
     */
    private function createEditForm(JournalSection $entity)
    {
        $form = $this->createForm(new JournalSectionType(), $entity, array(
            'action' => $this->generateUrl('manager_journal_section_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'user' => $this->getUser(),
            'journal' => $this->get('ojs.journal_service')->getSelectedJournal(),
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalSection entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('EDIT', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's section!");
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->successFlashBag('Successfully updated.');

            return $this->redirectToRoute('manager_journal_section_edit', [
                'id' => $id,
                ]
            );
        }

        return $this->render('OjsJournalBundle:JournalSection:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a JournalSection entity.
     *
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if(!$this->isGranted('DELETE', $journal, 'sections')) {
            throw new AccessDeniedException("You are not authorized for delete this journal's section!");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:JournalSection')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('manager_journal_section'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('Successfully removed.');

        return $this->redirectToRoute('manager_journal_section');
    }
}
