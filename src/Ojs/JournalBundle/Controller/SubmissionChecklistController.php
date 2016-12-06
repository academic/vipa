<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\SubmissionChecklist;
use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\JournalSubmissionChecklist\JournalSubmissionChecklistEvents;
use Ojs\JournalBundle\Event\ListEvent;
use Ojs\JournalBundle\Form\Type\SubmissionChecklistType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * SubmissionChecklist controller.
 *
 */
class SubmissionChecklistController extends Controller
{
    /**
     * Lists all SubmissionChecklist entities.
     *
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('VIEW', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $source = new Entity('OjsJournalBundle:SubmissionChecklist');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');

        $rowAction[] = $gridAction->showAction('ojs_journal_checklist_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_checklist_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction(
            'ojs_journal_checklist_delete',
            ['id', 'journalId' => $journal->getId()]
        );

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('OjsJournalBundle:SubmissionChecklist:index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sortAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $checklists = $em->getRepository(SubmissionChecklist::class)->findAll();
        usort($checklists, function($a, $b){
            return $a->getOrder() > $b->getOrder();
        });

        $sortData = [];
        foreach ($checklists as $checklist){
            $sortData[$checklist->getId()] = $checklist->getOrder();
        }

        if($request->getMethod() == 'POST' && $request->request->has('sortData')){
            $sortData = json_decode($request->request->get('sortData'));
            foreach ($sortData as $checklistId => $order){
                foreach ($checklists as $checklist){
                    if($checklist->getId() == $checklistId){
                        $checklist->setOrder($order);
                        $em->persist($checklist);
                    }
                }
            }
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_journal_checklist_sort', [
                'journalId' => $journal->getId(),
            ]);
        }

        return $this->render('OjsJournalBundle:SubmissionChecklist:sort.html.twig', [
                'checklists' => $checklists,
                'jsonSortData' => json_encode($sortData),
            ]
        );
    }

    /**
     * Creates a new SubmissionChecklist entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        if (!$this->isGranted('CREATE', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new SubmissionChecklist();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setJournal($journal);

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::PRE_CREATE, $event);

            $em->persist($entity);
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');

            return $this->redirect(
                $this->generateUrl(
                    'ojs_journal_checklist_show',
                    array('id' => $entity->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'OjsJournalBundle:SubmissionChecklist:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a SubmissionChecklist entity.
     *
     * @param  SubmissionChecklist          $entity    The entity
     * @param  integer                      $journalId
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SubmissionChecklist $entity, $journalId)
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
            new SubmissionChecklistType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_checklist_create', ['journalId' => $journalId]),
                'languages' => $languages,
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SubmissionChecklist entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('CREATE', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $entity = new SubmissionChecklist();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'OjsJournalBundle:SubmissionChecklist:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a SubmissionChecklist entity.
     *
     * @param  SubmissionChecklist $entity
     * @return Response
     */
    public function showAction(SubmissionChecklist $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_checklist'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:SubmissionChecklist:show.html.twig',
            array(
                'entity' => $entity,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing SubmissionChecklist entity.
     *
     * @param  SubmissionChecklist $entity
     * @return Response
     */
    public function editAction(SubmissionChecklist $entity)
    {
        $this->throw404IfNotFound($entity);
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:SubmissionChecklist:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a SubmissionChecklist entity.
     *
     * @param SubmissionChecklist $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(SubmissionChecklist $entity)
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
            new SubmissionChecklistType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_checklist_update',
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
     * Edits an existing SubmissionChecklist entity.
     *
     * @param  Request                   $request
     * @param  SubmissionChecklist       $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, SubmissionChecklist $entity)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl(
                    'ojs_journal_checklist_edit',
                    array('id' => $entity->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'OjsJournalBundle:SubmissionChecklist:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a SubmissionChecklist entity.
     *
     * @param  Request             $request
     * @param  SubmissionChecklist $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, SubmissionChecklist $entity)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $journal, 'checklist')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_checklist'.$entity->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $event = new JournalItemEvent($entity);
        $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::PRE_DELETE, $event);

        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $eventDispatcher->dispatch(JournalSubmissionChecklistEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $em->remove($entity);
        $em->flush();

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_checklist_index', ['journalId' => $journal->getId()]);
    }
}
