<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Block;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Form\Type\BlockType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Block controller.
 *
 */
class BlockController extends Controller
{
    /**
     * Lists all Block entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for view this journal's blocks!");
        }
        $source = new Entity('VipaJournalBundle:Block');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_journal_block_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('vipa_journal_block_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_block_delete', ['id', 'journalId' => $journal->getId()]);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('VipaJournalBundle:Block:index.html.twig');
    }

    /**
     * Creates a new Block entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new Block();
        $form = $this->createCreateForm($entity, $journal);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);

            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'vipa_journal_block_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:Block:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Block entity.
     *
     * @param  Block   $entity  The entity
     * @param  Journal $journal
     * @return Form    The form
     */
    private function createCreateForm(Block $entity, Journal $journal)
    {
        $form = $this->createForm(
            new BlockType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_journal_block_create', ['journalId' => $journal->getId()]),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Block entity.
     *
     */
    public function newAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for this page");
        }
        $entity = new Block();
        $form = $this->createCreateForm($entity, $journal);

        return $this->render(
            'VipaJournalBundle:Block:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param  Block    $entity
     * @return Response
     *
     */
    public function showAction(Block $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for view this page!");
        }
        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_block'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:Block:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing Block entity.
     *
     *
     * @param  Block    $block
     * @return Response
     *
     */
    public function editAction(Block $block)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for edit this page!");
        }

        $editForm = $this->createEditForm($block);

        return $this->render(
            'VipaJournalBundle:Block:edit.html.twig',
            array(
                'entity' => $block,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Block entity.
     *
     * @param Block $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Block $entity)
    {
        $form = $this->createForm(
            new BlockType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_journal_block_update',
                    array('id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId())
                ),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Block entity.
     *
     * @param  Request                   $request
     * @param  Block                     $entity
     * @return RedirectResponse|Response
     *
     *
     */
    public function updateAction(Request $request, Block $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for edit this page!");
        }
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute(
                'vipa_journal_block_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:Block:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Block entity.
     *
     * @param  Request          $request
     * @param  Block            $entity
     * @return RedirectResponse
     *
     *
     */
    public function deleteAction(Request $request, Block $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('DELETE', $journal, 'block')) {
            throw new AccessDeniedException("You not authorized for delete this page!");
        }

        $em = $this->getDoctrine()->getManager();

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_block'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);

        $em->remove($entity);
        $em->flush();

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_journal_block_index', ['journalId' => $journal->getId()]);
    }
}
