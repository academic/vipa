<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Board;
use Ojs\JournalBundle\Entity\BoardMember;
use Ojs\JournalBundle\Form\Type\BoardType;
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
        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($ta, $journal) {
                $query->andWhere($ta.'.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_board_show', 'id');
        if ($this->isGranted('EDIT', $journal, 'boards')) {
            $rowAction[] = $gridAction->editAction('ojs_journal_board_edit', 'id');
            $rowAction[] = $gridAction->deleteAction('ojs_journal_board_delete', 'id');
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
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_journal_board_show', ['id' => $entity->getId()]);
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
     * @param Board $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Board $entity)
    {
        $form = $this->createForm(
            new BoardType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_board_create'),
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
        $form = $this->createCreateForm($entity);

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
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for view this journal's boards!");
        }
        $em = $this->getDoctrine()->getManager();
        $board = $em->getRepository('OjsJournalBundle:Board')->find(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($board);
        $members = $em->getRepository('OjsJournalBundle:BoardMember')->findBy(array('board' => $board));

        return $this->render(
            'OjsJournalBundle:Board:show.html.twig',
            array(
                'members' => $members,
                'entity' => $board,
            )
        );
    }

    /**
     * Displays a form to edit an existing Board entity.
     *
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's boards!");
        }
        $em = $this->getDoctrine()->getManager();
        /** @var Board $entity */
        $entity = $em->getRepository('OjsJournalBundle:Board')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:Board:edit.html.twig',
            array(
                'entity' => $entity,
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
                'action' => $this->generateUrl('ojs_journal_board_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Board entity.
     *
     * @param  Request                                                     $request
     * @param $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's boards!");
        }
        $em = $this->getDoctrine()->getManager();
        /** @var Board $entity */
        $entity = $em->getRepository('OjsJournalBundle:Board')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_journal_board_edit', ['id' => $id]);
        }

        return $this->render(
            'OjsJournalBundle:Board:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Board entity.
     *
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('DELETE', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for delete this journal's boards!");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Board')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_board_index'.$id); //@todo amin_board \ admin_board
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_board_index');
    }

    /**
     *  add posted user id  as board member with given board id
     * @param  Request          $req
     * @param  int              $boardId
     * @return RedirectResponse
     */
    public function addMemberAction(Request $req, $boardId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's board!");
        }
        $userId = $req->get('userid');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('OjsUserBundle:User')->find($userId);
        $this->throw404IfNotFound($user);
        /** @var Board $board */
        $board = $em->getRepository('OjsJournalBundle:Board')->findOneBy(
            array('id' => $boardId, 'journal' => $journal)
        );
        $seq = (int) $req->get('seq');
        $boardMember = new BoardMember();
        $boardMember->setBoard($board);
        $boardMember->setUser($user);
        $boardMember->setSeq($seq);
        $em->persist($boardMember);
        $em->flush();

        return $this->redirectToRoute('ojs_journal_board_show', ['id' => $boardId]);
    }

    /**
     * @param  int              $boardId
     * @param  int              $userId
     * @return RedirectResponse
     */
    public function removeMemberAction($boardId, $userId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'boards')) {
            throw new AccessDeniedException("You not authorized for edit this journal's board!");
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('OjsUserBundle:User')->find($userId);
        $this->throw404IfNotFound($user);

        $board = $em->getRepository('OjsJournalBundle:Board')->findOneBy(
            array('id' => $boardId, 'journal' => $journal)
        );
        $this->throw404IfNotFound($board);

        $boardMember = $em->getRepository('OjsJournalBundle:BoardMember')->findOneBy(
            array(
                'user' => $user,
                'board' => $board,
            )
        );
        $this->throw404IfNotFound($boardMember);
        $em->remove($boardMember);
        $em->flush();

        return $this->redirectToRoute('ojs_journal_board_show', ['id' => $boardId]);
    }
}
