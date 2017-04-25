<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Vipa\CoreBundle\Controller\VipaController;
use Vipa\JournalBundle\Entity\JournalPage;
use Vipa\JournalBundle\Event\JournalEvent;
use Vipa\JournalBundle\Event\JournalItemEvent;
use Vipa\JournalBundle\Event\JournalPage\JournalPageEvents;
use Vipa\JournalBundle\Event\ListEvent;
use Vipa\JournalBundle\Form\Type\JournalPageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class JournalPageController extends VipaController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');
        if (!$this->isGranted('VIEW', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $source = new Entity('VipaJournalBundle:JournalPage');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('vipa_journal_page_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('vipa_journal_page_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('vipa_journal_page_delete', ['id', 'journalId' => $journal->getId()]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(JournalPageEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('VipaJournalBundle:JournalPage:index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sortAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $pages = $em->getRepository(JournalPage::class)->findAll();
        usort($pages, function($a, $b){
            return $a->getPageOrder() > $b->getPageOrder();
        });

        $sortData = [];
        foreach ($pages as $page){
            $sortData[$page->getId()] = $page->getPageOrder();
        }

        if($request->getMethod() == 'POST' && $request->request->has('sortData')){
            $sortData = json_decode($request->request->get('sortData'));
            foreach ($sortData as $pageId => $order){
                foreach ($pages as $page){
                    if($page->getId() == $pageId){
                        $page->setPageOrder($order);
                        $em->persist($page);
                    }
                }
            }
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_journal_page_sort', [
                'journalId' => $journal->getId(),
            ]);
        }

        return $this->render('VipaJournalBundle:JournalPage:sort.html.twig', [
                'pages' => $pages,
                'jsonSortData' => json_encode($sortData),
            ]
        );
    }

    /**
     * Displays a form to create a new JournalPage entity.
     */
    public function newAction()
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new JournalPage();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalPage:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a JournalPage entity.
     *
     * @param JournalPage $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(JournalPage $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $form = $this->createForm(
            new JournalPageType(),
            $entity,
            [
                'action' => $this->generateUrl('vipa_journal_page_create', ['journalId' => $journal->getId()]),
                'method' => 'POST'
            ]
        );
        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a new JournalPage entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $eventDispatcher = $this->get('event_dispatcher');

        if (!$this->isGranted('CREATE', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new JournalPage();
        $entity->setCurrentLocale($request->getDefaultLocale());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);
            $em = $this->getDoctrine()->getManager();

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalPageEvents::PRE_CREATE, $event);

            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalPageEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute(
                'vipa_journal_page_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalPage:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a JournalPage entity.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalPage')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_page'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:JournalPage:show.html.twig',
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
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalPage')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_page'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'VipaJournalBundle:JournalPage:edit.html.twig',
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
     * @param  JournalPage $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(JournalPage $entity)
    {
        $form = $this->createForm(
            new JournalPageType(),
            $entity,
            [
                'action' => $this->generateUrl(
                    'vipa_journal_page_update',
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

        /** @var JournalPage $entity */
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalPage')
            ->find($id);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $event = new JournalItemEvent($entity);
            $eventDispatcher->dispatch(JournalPageEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();

            $event = new JournalItemEvent($event->getItem());
            $eventDispatcher->dispatch(JournalPageEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            $this->successFlashBag('successful.update');
            return $this->redirectToRoute(
                'vipa_journal_page_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'VipaJournalBundle:JournalPage:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param Request $request
     * @param JournalPage $entity
     * @return Response
     */
    public function deleteAction(Request $request, JournalPage $entity)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $dispatcher = $this->get('event_dispatcher');

        if (!$this->isGranted('DELETE', $journal, 'pages')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_page'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }

        $event = new JournalItemEvent($entity);
        $dispatcher->dispatch(JournalPageEvents::PRE_DELETE, $event);

        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();

        $event = new JournalEvent($journal);
        $dispatcher->dispatch(JournalPageEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $this->successFlashBag('successful.remove');
        return $this->redirectToRoute('vipa_journal_page_index', ['journalId' => $journal->getId()]);
    }
}
