<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Vipa\CoreBundle\Controller\VipaController;
use Vipa\JournalBundle\Entity\JournalPost;
use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\JournalPost\JournalPostEvents;
use Vipa\JournalBundle\Event\ListEvent;
use Vipa\JournalBundle\Form\Type\JournalPostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class JournalPostController extends VipaController
{
    /**
     * Lists all JournalPost entities.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('VIEW', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }
        $source = new Entity('VipaJournalBundle:JournalPost');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('vipa_journal_post_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('vipa_journal_post_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_post_delete', ['id', 'journalId' => $journal->getId()]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalPostEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('VipaJournalBundle:JournalPost:index.html.twig');
    }

    /**
     * Displays a form to create a new JournalPost entity.
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $entity = new JournalPost();
        $entity->setCurrentLocale($request->getDefaultLocale());
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalPost:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a JournalPost entity.
     *
     * @param JournalPost $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalPost $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $form = $this->createForm(
            new JournalPostType(),
            $entity,
            [
                'action' => $this->generateUrl('vipa_journal_post_create', ['journalId' => $journal->getId()]),
                'method' => 'POST'
            ]
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new JournalPost entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $journalMandatoryLocale = $journal->getMandatoryLang()->getCode();
        $eventDispatcher = $this->get('event_dispatcher');

        if (!$this->isGranted('CREATE', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $entity = new JournalPost();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setCurrentLocale($journalMandatoryLocale);
            $entity->setJournal($journal);

            $em = $this->getDoctrine()->getManager();
            $entity->setSlug($entity->getTranslationByLocale($journalMandatoryLocale)->getTitle());

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalPostEvents::PRE_CREATE, $event);

            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalPostEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                'vipa_journal_post_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalPost:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a JournalPost entity.
     *
     * @param  JournalPost $journalPost
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JournalPost $journalPost)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_post'.$journalPost->getId());

        return $this->render(
            'VipaJournalBundle:JournalPost:show.html.twig',
            ['entity' => $journalPost, 'token' => $token]
        );
    }

    /**
     * Displays a form to edit an existing Lang entity.
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalPost')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_post'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalPost:edit.html.twig',
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
     * @param  JournalPost $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalPost $entity)
    {
        $form = $this->createForm(
            new JournalPostType(),
            $entity,
            [
                'action' => $this->generateUrl(
                    'vipa_journal_post_update',
                    ['id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId()]
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

        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalPost')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setSlug($entity->getTranslationByLocale($request->getDefaultLocale())->getTitle());

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalPostEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalPostEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.update');

            return $this->redirectToRoute(
                'vipa_journal_post_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalPost:edit.html.twig',
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

        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalPost')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_post'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }

        $event = new JournalItemEvent($entity);
        $eventDispatcher->dispatch(JournalPostEvents::PRE_DELETE, $event);

        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $eventDispatcher->dispatch(JournalPostEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('vipa_journal_post_index', ['journalId' => $journal->getId()]);
    }
}
