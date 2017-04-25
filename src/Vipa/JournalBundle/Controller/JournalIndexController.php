<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\JournalIndex;
use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalIndex\JournalIndexEvents;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\ListEvent;
use Vipa\JournalBundle\Form\Type\JournalIndexType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * JournalIndex controller.
 *
 */
class JournalIndexController extends Controller
{
    /**
     * Lists all JournalIndex entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('VIEW', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $source = new Entity('VipaJournalBundle:JournalIndex');

        $source->manipulateRow(
            function (Row $row) use ($request) {
                /* @var JournalIndex $entity */
                $entity = $row->getEntity();
                if (!is_null($entity)) {
                    $entity->getJournal()->setDefaultLocale($request->getDefaultLocale());
                    $row->setField('journal', $entity->getJournal()->getTitle());
                }

                return $row;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_journal_index_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('vipa_journal_index_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_index_delete', ['id', 'journalId' => $journal->getId()]);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalIndexEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('VipaJournalBundle:JournalIndex:index.html.twig');
    }

    /**
     * Creates a new JournalIndex entity.
     *
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('CREATE', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = new JournalIndex();
        $entity->setJournal($journal);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalIndexEvents::PRE_CREATE, $event);

            $em->persist($entity);
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalIndexEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');

            return $this->redirect(
                $this->generateUrl(
                    'vipa_journal_index_show',
                    array('id' => $entity->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        $this->successFlashBag('successful.create');

        return $this->render(
            'VipaJournalBundle:JournalIndex:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a JournalIndex entity.
     *
     * @param JournalIndex $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalIndex $entity)
    {
        $form = $this->createForm(
            new JournalIndexType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_journal_index_create',
                    ['journalId' => $entity->getJournal()->getId()]
                ),
                'method' => 'POST'
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new JournalIndex entity.
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalIndex();
        $entity->setJournal($journal);
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalIndex:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a JournalIndex entity.
     *
     * @param  JournalIndex $entity
     * @return Response
     */
    public function showAction(JournalIndex $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_index'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:JournalIndex:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing JournalIndex entity.
     *
     * @param  JournalIndex $entity
     * @return Response
     */
    public function editAction(JournalIndex $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalIndex:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a JournalIndex entity.
     *
     * @param JournalIndex $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalIndex $entity)
    {
        $form = $this->createForm(
            new JournalIndexType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_journal_index_update',
                    array('id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId())
                ),
                'method' => 'PUT'
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing JournalIndex entity.
     *
     * @param  Request $request
     * @param  JournalIndex $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, JournalIndex $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalIndexEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalIndexEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl(
                    'vipa_journal_index_edit',
                    array('id' => $entity->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalIndex:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  JournalIndex $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, JournalIndex $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $journal, 'index')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_index'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $event = new JournalItemEvent($entity);
        $eventDispatcher->dispatch(JournalIndexEvents::PRE_DELETE, $event);

        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $eventDispatcher->dispatch(JournalIndexEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_journal_index_index', ['journalId' => $journal->getId()]);
    }
}
