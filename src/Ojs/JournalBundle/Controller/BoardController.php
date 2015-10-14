<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Board;
use Ojs\JournalBundle\Entity\BoardMember;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\Type\BoardType;
use Ojs\JournalBundle\Form\Type\BoardMemberType;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Board controller.
 *
 */
class BoardController extends Controller
{

    /**
     * Lists all Board entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for view this journal's boards!");
        }
        $source = new Entity('OjsJournalBundle:Board');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_board_show', ['id', 'journalId' => $journal->getId()]);
        if ($this->isGranted('EDIT', $journal, 'boards')) {
            $rowAction[] = $gridAction->editAction('ojs_journal_board_edit', ['id', 'journalId' => $journal->getId()]);
            $rowAction[] = $gridAction->deleteAction(
                'ojs_journal_board_delete',
                ['id', 'journalId' => $journal->getId()]
            );
        }
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Board:index.html.twig', $data);
    }

    /**
     * Creates a new Board entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for create this journal's boards!");
        }
        $entity = new Board();
        $form = $this->createCreateForm($entity, $journal);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'ojs_journal_board_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'OjsJournalBundle:Board:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Board entity.
     *
     * @param  Board   $entity  The entity
     * @param  Journal $journal
     * @return Form    The form
     */
    private function createCreateForm(Board $entity, Journal $journal)
    {
        $form = $this->createForm(
            new BoardType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_board_create', ['journalId' => $journal->getId()]),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Board entity.
     *
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for create this journal's boards!");
        }
        $entity = new Board();
        $form = $this->createCreateForm($entity, $journal);

        return $this->render(
            'OjsJournalBundle:Board:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a board and it's details.
     *  This page is also an arrangement page for a board.
     * In this page journal manager can :
     *              - list members
     *              - add members to a board
     *              - change orders of the members
     *
     * @param  Board    $board
     * @return Response
     */
    public function showAction(Board $board)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for view this journal's boards!");
        }

        $boardMember = new BoardMember();
        $addMemberForm = $this->createAddMemberForm($boardMember, $board, $journal);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_board'.$board->getId());

        $source = new Entity('OjsJournalBundle:BoardMember');

        $membersGrid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction = [];
        if ($this->isGranted('EDIT', $journal, 'boards')) {
            $rowAction[] = $gridAction->deleteAction(
                'ojs_journal_board_member_remove',
                ['id', 'journalId' => $journal->getId(), 'boardId' => $board->getId()]
            );
        }
        $actionColumn->setRowActions($rowAction);
        $membersGrid->addColumn($actionColumn);

        return $membersGrid->getGridResponse(
            'OjsJournalBundle:Board:show.html.twig',
            array(
                'membersGrid' => $membersGrid,
                'entity' => $board,
                'token' => $token,
                'addMemberForm' => $addMemberForm->createView()
            )
        );
    }

    /**
     * Displays a form to edit an existing Board entity.
     *
     *
     * @param  Board    $board
     * @return Response
     */
    public function editAction(Board $board)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's boards!");
        }

        $editForm = $this->createEditForm($board);

        return $this->render(
            'OjsJournalBundle:Board:edit.html.twig',
            array(
                'entity' => $board,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Board entity.
     *
     * @param Board $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Board $entity)
    {
        $form = $this->createForm(
            new BoardType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_board_update',
                    array('id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId())
                ),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Creates a form to add Member to Board entity.
     *
     * @param BoardMember $entity
     * @param Journal $journal
     * @return Form
     */
    private function createAddMemberForm(BoardMember $entity,Board $board, Journal $journal)
    {
        $form = $this->createForm(
            new BoardMemberType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_board_member_add',
                    array('boardId' => $board->getId(), 'journalId' => $journal->getId())
                ),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Board entity.
     *
     * @param  Request                   $request
     * @param  Board                     $board
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Board $board)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's boards!");
        }
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($board);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute(
                'ojs_journal_board_edit',
                ['id' => $board->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'OjsJournalBundle:Board:edit.html.twig',
            array(
                'entity' => $board,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Board entity.
     *
     * @param  Request          $request
     * @param  Board            $board
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Board $board)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for delete this journal's boards!");
        }
        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_board'.$board->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($board);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_board_index', ['journalId' => $journal->getId()]);
    }

    /**
     *  add posted user id  as board member with given board id
     * @param  Request          $request
     * @param  $boardId
     * @return RedirectResponse
     */
    public function addMemberAction(Request $request, $boardId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's board!");
        }
        $em = $this->getDoctrine()->getManager();
        $board = $em->getRepository('OjsJournalBundle:Board')->find($boardId);

        $boardMember = new BoardMember();
        $addMemberForm = $this->createAddMemberForm($boardMember, $board, $journal);
        $addMemberForm->handleRequest($request);

        if ($addMemberForm->isValid()) {
            $boardMember->setBoard($board);
            $em->persist($boardMember);
            $em->flush();
            $this->successFlashBag('successful.create');
        }
        return $this->redirectToRoute(
            'ojs_journal_board_show',
            ['id' => $board->getId(), 'journalId' => $journal->getId()]
        );
    }

    /**
     * @param  $boardId
     * @param  $id
     * @return RedirectResponse
     */
    public function removeMemberAction($boardId, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's board!");
        }
        $em = $this->getDoctrine()->getManager();

        $boardMember = $em->getRepository('OjsJournalBundle:BoardMember')->find($id);
        $this->throw404IfNotFound($boardMember);
        $em->remove($boardMember);
        $em->flush();

        return $this->redirectToRoute(
            'ojs_journal_board_show',
            ['id' => $boardId, 'journalId' => $journal->getId()]
        );
    }
}
