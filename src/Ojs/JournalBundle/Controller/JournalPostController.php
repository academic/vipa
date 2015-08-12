<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ojs\JournalBundle\Entity\JournalPost;
use Ojs\CmsBundle\Form\Type\PostType;
use Ojs\Common\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

class JournalPostController extends OjsController
{
    /**
     * Lists all JournalPost entities.
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $source = new Entity('OjsJournalBundle:JournalPost');
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
        $rowAction[] = $gridAction->showAction('ojs_journal_post_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_post_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->deleteAction('ojs_journal_post_delete', ['id', 'journalId' => $journal->getId()]);
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:JournalPost:index.html.twig', ['grid' => $grid]);
    }

    /**
     * Displays a form to create a new JournalPost entity.
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $entity = new JournalPost();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalPost:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a new JournalPost entity.
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $entity = new JournalPost();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setTranslatableLocale($request->getDefaultLocale());
            $entity->setJournal($journal);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');
            return $this->redirectToRoute('ojs_journal_post_show',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalPost:new.html.twig',
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
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $form = $this->createForm(new PostType($this->container), $entity,
            [
                'action' => $this->generateUrl('ojs_journal_post_create', ['journalId' => $journal->getId()]),
                'method' => 'POST'
            ]);
        $form->add('submit', 'submit', ['label' => 'Create']);
        return $form;
    }

    /**
     * Finds and displays a JournalPost entity.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        /** @var JournalPost $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalPost')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_post'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:JournalPost:show.html.twig',
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
        /** @var JournalPost $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalPost')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_post'.$entity->getId());

        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:JournalPost:edit.html.twig',
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
        /** @var JournalPost $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalPost')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('EDIT', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');
            return $this->redirectToRoute('ojs_journal_post_edit',
                ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:JournalPost:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
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
        $form = $this->createForm(new PostType($this->container), $entity,
            [
                'action' => $this->generateUrl('ojs_journal_post_update',
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
        /** @var JournalPost $entity */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:JournalPost')
            ->findOneBy(['id' => $id, 'journal' => $journal]);
        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('DELETE', $journal, 'posts')) {
            throw new AccessDeniedException("You are not authorized for this post!");
        }

        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_post'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token not found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_post_index', ['journalId' => $journal->getId()]);
    }
}