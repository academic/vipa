<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Board;
use Ojs\JournalBundle\Form\BoardType;

/**
 * Board manager controller.
 *
 */
class BoardManagerController extends Controller {

    /**
     * Lists all Board entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:Board')->findByJournalId($journal->getId());
        return $this->render('OjsManagerBundle:BoardManager:index.html.twig', array(
                    'entities' => $entities,
                    'journal' => $journal
        ));
    }

    /**
     * 
     * @param int $boardId
     * @param  int $userId
     * @return RedirectResponse
     */
    public function removeMemberAction($boardId, $userId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('OjsUserBundle:User')->find($userId);
        $board = $this->getBoard($boardId);
        $boardMember = $em->getRepository('OjsJournalBundle:BoardMember')->findOneBy(array(
            'user' => $user,
            'board' => $board
        ));
        $this->throw404IfNotFound($boardMember);
        $em->remove($boardMember);
        $em->flush();
        return $this->redirect($this->generateUrl('board_manager_show', array('id' => $boardId)));
    }

    /**
     *  add posted userid  as boardmember with given boardid
     * @param Request $req
     * @param int $boardId
     * @return RedirectResponse
     */
    public function addMemberAction(Request $req, $boardId)
    {
        $userId = $req->get('userid');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('OjsUserBundle:User')->find($userId);
        $this->throw404IfNotFound($user);
        $board = $this->getBoard($boardId);
        $seq = (int) $req->get('seq');
        $boardMember = new \Ojs\JournalBundle\Entity\BoardMember();
        $boardMember->setBoard($board);
        $boardMember->setUser($user);
        $boardMember->setSeq($seq);
        $em->persist($boardMember);
        $em->flush();
        return $this->redirect($this->generateUrl('board_manager_show', array('id' => $boardId)));
    }

    /**
     * 
     * @param int $id
     * @return Board
     *  @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getBoard($id)
    {
        $em = $this->getDoctrine()->getManager();
        $board = $em->getRepository('OjsJournalBundle:Board')->find($id);
        if (!$board) {
            throw $this->createNotFoundException('notFound');
        }
        return $board;
    }

    /**
     * Creates a new Board entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Board();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('Successfully created.');
            return $this->redirectToRoute('board_manager_show', [
                'id' => $entity->getId()
            ]
            );
        }

        return $this->render('OjsManagerBundle:Board:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Board entity.
     *
     * @param Board $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Board $entity)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $form = $this->createForm(new BoardType(), $entity, array(
            'action' => $this->generateUrl('board_manager_create'),
            'method' => 'POST',
            'journal' => $journal
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Board entity.
     *
     */
    public function newAction()
    {
        $entity = new Board();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Board:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a board and it's details. 
     *  This page is also an arrangement page for a board. 
     * In this page journal manager can : 
     *              - list members
     *              - add members to a board
     *              - change orders of the members
     */
    public function showAction($id)
    {
        $board = $this->getBoard($id);
        $members = $this->getDoctrine()->getManager()
                        ->getRepository('OjsJournalBundle:BoardMember')->findByBoard($board);

        return $this->render('OjsManagerBundle:BoardManager:show.html.twig', array(
                    'members' => $members,
                    'journal' => $this->get('ojs.journal_service')->getSelectedJournal(),
                    'entity' => $board,
        ));
    }

    /**
     * Displays a form to edit an existing Board entity.
     * @param int $id
     *  @return Response
     */
    public function editAction($id)
    {
        $board = $this->getBoard($id);
        $editForm = $this->createEditForm($board);
        return $this->render('OjsJournalBundle:Board:edit.html.twig', array(
                    'entity' => $board,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Board entity.
     * @param Board $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Board $entity)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $form = $this->createForm(new BoardType(), $entity, array(
            'action' => $this->generateUrl('board_manager_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'journal' => $journal
        ));
        return $form;
    }

    /**
     * Edits an existing Board entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $board = $this->getBoard($id);
        $editForm = $this->createEditForm($board);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('Successfully updated.');
            return $this->redirectToRoute('board_manager_edit', [
                'id' => $id
                ]
            );
        }
        return $this->render('OjsJournalBundle:Board:edit.html.twig', array(
                    'entity' => $board,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Board entity.
     * @param int $id
     * @return ResponseRedirect
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $board = $this->getBoard($id);
        $em->remove($board);
        $em->flush();

        $this->successFlashBag('Successfully removed.');
        return $this->redirectToRoute('board_manager');
    }

}
