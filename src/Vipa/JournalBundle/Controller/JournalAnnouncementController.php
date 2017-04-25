<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Vipa\CoreBundle\Controller\VipaController;
use Vipa\JournalBundle\Entity\JournalAnnouncement;
use Vipa\JournalBundle\Event\JournalAnnouncement\JournalAnnouncementEvents;
use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\ListEvent;
use Vipa\JournalBundle\Form\Type\JournalAnnouncementType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class JournalAnnouncementController extends VipaController
{
    /**
     * Lists all JournalAnnouncement entities.
     */
    public function indexAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        if (!$this->isGranted('VIEW', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $source = new Entity('VipaJournalBundle:JournalAnnouncement');

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction(
            'vipa_journal_announcement_show',
            ['id', 'journalId' => $journal->getId()]
        );
        $rowAction[] = $gridAction->editAction(
            'vipa_journal_announcement_edit',
            ['id', 'journalId' => $journal->getId()]
        );
        $rowAction[] = $gridAction->deleteAction(
            'vipa_journal_announcement_delete',
            ['id', 'journalId' => $journal->getId()]
        );
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalAnnouncementEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('VipaJournalBundle:JournalAnnouncement:index.html.twig');
    }

    /**
     * Displays a form to create a new JournalAnnouncement entity.
     */
    public function newAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new JournalAnnouncement();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalAnnouncement:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a JournalAnnouncement entity.
     *
     * @param JournalAnnouncement $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalAnnouncement $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $form = $this->createForm(
            new JournalAnnouncementType(),
            $entity,
            [
                'action' => $this->generateUrl('vipa_journal_announcement_create', ['journalId' => $journal->getId()]),
                'method' => 'POST'
            ]
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new JournalAnnouncement entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        if (!$this->isGranted('CREATE', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new JournalAnnouncement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);
            $entity->setCurrentLocale($journal->getMandatoryLang()->getCode());
            $em = $this->getDoctrine()->getManager();

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalAnnouncementEvents::PRE_CREATE, $event);

            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalAnnouncementEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'vipa_journal_announcement_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalAnnouncement:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a JournalAnnouncement entity.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        /** @var JournalAnnouncement $entity */
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalAnnouncement')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_announcement'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:JournalAnnouncement:show.html.twig',
            ['entity' => $entity, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Lang entity.
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        /** @var JournalAnnouncement $entity */
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalAnnouncement')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_announcement'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalAnnouncement:edit.html.twig',
            [
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to edit a Lang entity.
     *
     * @param  JournalAnnouncement $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalAnnouncement $entity)
    {
        $form = $this->createForm(
            new JournalAnnouncementType(),
            $entity,
            [
                'action' => $this->generateUrl(
                    'vipa_journal_announcement_update',
                    [
                        'id' => $entity->getId(),
                        'journalId' => $entity->getJournal()->getId()
                    ]
                ),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Lang entity.
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        /** @var JournalAnnouncement $entity */
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalAnnouncement')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalAnnouncementEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalAnnouncementEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.update');

            return $this->redirectToRoute(
                'vipa_journal_announcement_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalAnnouncement:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        /** @var JournalAnnouncement $entity */
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalAnnouncement')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_announcement'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);

        $event = new JournalItemEvent($entity);
        $eventDispatcher->dispatch(JournalAnnouncementEvents::PRE_DELETE, $event);

        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $eventDispatcher->dispatch(JournalAnnouncementEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_journal_announcement_index', ['journalId' => $journal->getId()]);
    }
}
