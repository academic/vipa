<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\JournalSubmissionFile;
use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\JournalSubmissionFile\JournalSubmissionFileEvents;
use Vipa\JournalBundle\Event\ListEvent;
use Vipa\JournalBundle\Form\Type\JournalSubmissionFileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * JournalSubmissionFile controller.
 *
 */
class JournalSubmissionFileController extends Controller
{
    /**
     * Lists all SubmissionFile entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('VIEW', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        if (!$journal) {
            throw new NotFoundHttpException("Journal not found!");
        }
        $source = new Entity('VipaJournalBundle:JournalSubmissionFile');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('vipa_journal_file_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('vipa_journal_file_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_file_delete', ['id', 'journalId' => $journal->getId()]);

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalSubmissionFileEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('VipaJournalBundle:SubmissionFile:index.html.twig');
    }

    /**
     * Creates a new SubmissionFile entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('CREATE', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalSubmissionFile();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setJournal($journal);

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalSubmissionFileEvents::PRE_CREATE, $event);

            $em->persist($entity);
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalSubmissionFileEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');

            return $this->redirect(
                $this->generateUrl(
                    'vipa_journal_file_show',
                    array('id' => $entity->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:SubmissionFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @param  JournalSubmissionFile        $entity
     * @param $journalId
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(JournalSubmissionFile $entity, $journalId)
    {
        $languages = [];
        if (is_array($this->container->getParameter('languages'))) {
            foreach ($this->container->getParameter('languages') as $key => $language) {
                if (array_key_exists('code', $language)) {
                    $languages[$language['code']] = $language['name'];
                }
            }
        }
        $form = $this->createForm(
            new JournalSubmissionFileType(),
            $entity,
            array(
                'action' => $this->generateUrl('vipa_journal_file_create', ['journalId' => $journalId]),
                'languages' => $languages,
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SubmissionFile entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new JournalSubmissionFile();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'VipaJournalBundle:SubmissionFile:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a SubmissionFile entity.
     *
     * @param  JournalSubmissionFile                      $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JournalSubmissionFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_file'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:SubmissionFile:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing SubmissionFile entity.
     *
     * @param  JournalSubmissionFile                      $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(JournalSubmissionFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:SubmissionFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a SubmissionFile entity.
     *
     * @param  JournalSubmissionFile        $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(JournalSubmissionFile $entity)
    {
        $languages = [];
        if (is_array($this->container->getParameter('languages'))) {
            foreach ($this->container->getParameter('languages') as $key => $language) {
                if (array_key_exists('code', $language)) {
                    $languages[$language['code']] = $language['name'];
                }
            }
        }

        $form = $this->createForm(
            new JournalSubmissionFileType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_journal_file_update',
                    array('id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId())
                ),
                'languages' => $languages,
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing SubmissionFile entity.
     *
     * @param  Request                   $request
     * @param  JournalSubmissionFile     $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, JournalSubmissionFile $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalSubmissionFileEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalSubmissionFileEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl(
                    'vipa_journal_file_edit',
                    array('id' => $entity->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:SubmissionFile:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a SubmissionFile entity.
     *
     * @param  Request               $request
     * @param  JournalSubmissionFile $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, JournalSubmissionFile $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $journal, 'file')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_file'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $event = new JournalItemEvent($entity);
        $eventDispatcher->dispatch(JournalSubmissionFileEvents::PRE_DELETE, $event);

        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $eventDispatcher->dispatch(JournalSubmissionFileEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_journal_file_index', ['journalId' => $journal->getId()]);
    }
}
