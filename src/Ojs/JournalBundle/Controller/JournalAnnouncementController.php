<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Ojs\CmsBundle\Form\Type\AnnouncementType;
use Ojs\Common\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class JournalAnnouncementController extends OjsController
{
    /**
     * Lists all JournalAnnouncement entities.
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:JournalAnnouncement');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $alias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($alias, $journal) {
                $query
                    ->andWhere($alias.'.journal = :journal')
                    ->setParameter('journal', $journal);
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_announcement_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_announcement_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_announcement_delete', ['id', 'journalId' => $journal->getId()]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:JournalAnnouncement:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Displays a form to create a new JournalAnnouncement entity.
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new JournalAnnouncement();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalAnnouncement:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a new JournalAnnouncement entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $entity = new JournalAnnouncement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('ojs_journal_announcement_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalAnnouncement:new.html.twig',
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
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createForm(new AnnouncementType(), $entity,
            [
                'action' => $this->generateUrl('ojs_journal_announcement_create', ['journalId' => $journal->getId()]),
                'method' => 'POST'
            ]);
        $form->add('submit', 'submit', ['label' => 'Create']);
        return $form;
    }

    /**
     * Finds and displays a JournalAnnouncement entity.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        /** @var JournalAnnouncement $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalAnnouncement')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_announcement'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:JournalAnnouncement:show.html.twig',
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
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalAnnouncement')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_announcement'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalAnnouncement:edit.html.twig',
            [
                'token' => $token,
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Edits an existing Lang entity.
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        /** @var JournalAnnouncement $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalAnnouncement')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('ojs_journal_announcement_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalAnnouncement:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
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
        $form = $this->createForm(new AnnouncementType(), $entity,
            [
                'action' => $this->generateUrl('ojs_journal_announcement_update',
                    ['id' => $entity->getId(), 'journalId' => $entity->getJournal()->getId()]),
                'method' => 'PUT',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Update']);
        return $form;
    }

    /**
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var JournalAnnouncement $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalAnnouncement')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $journal, 'announcements')) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_announcement'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_announcement_index', ['journalId' => $journal->getId()]);
    }
}