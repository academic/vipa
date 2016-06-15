<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Event\JournalItemEvent;
use Ojs\JournalBundle\Event\Article\ArticleEvents;
use Ojs\JournalBundle\Event\JournalEvent;
use Ojs\JournalBundle\Event\ListEvent;
use Ojs\JournalBundle\Form\Type\ArticleType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * Article controller
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities for journal
     *
     * @param  Request  $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $cache = $this->get('array_cache');
        $eventDispatcher = $this->get('event_dispatcher');
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:Article');
        $source->manipulateRow(
            function (Row $row) use ($request, $cache) {
                /** @var Article $entity */
                $entity = $row->getEntity();
                if (!is_null($entity)) {
                    $entity->setDefaultLocale($request->getDefaultLocale());
                    $doi = $entity->getDoi();
                    if($cache->contains('grid_row_id_'.$entity->getId())){
                        $row->setClass('hidden');
                    }else{
                        $cache->save('grid_row_id_'.$entity->getId(), true);
                        if ($doi !== null) {
                            $row->setField('translations.title', $entity->getTitleTranslations().' / '.$doi);
                        } else {
                            $row->setField('translations.title', $entity->getTitleTranslations());
                        }
                        if (!is_null($entity->getIssue())) {
                            $row->setField('issue.translations.title', $entity->getIssue()->getTitleTranslations());
                        }
                    }
                }

                return $row;
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_article_show', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->editAction('ojs_journal_article_edit', ['id', 'journalId' => $journal->getId()]);
        $rowAction[] = $gridAction->articleCitations($journal->getId());
        $rowAction[] = $gridAction->articleAuthors($journal->getId());
        $rowAction[] = $gridAction->articleFiles($journal->getId());

        $rowAction[] = $gridAction->deleteAction(
            'ojs_journal_article_delete',
            ['id', 'journalId' => $journal->getId()]
        );
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $grid->getColumn('numerator')->manipulateRenderCell(
            function ($value, $row, $router) use ($journal) {
                if ($journal->getTitleAbbr() !== null) {
                    return $journal->getTitleAbbr().'.'.$value;
                } else {
                    return $journal->getSlug().'.'.$value;
                }
            }
        );

        $listEvent = new ListEvent();
        $listEvent->setGrid($grid);
        $eventDispatcher->dispatch(ArticleEvents::LISTED, $listEvent);
        $grid = $listEvent->getGrid();

        return $grid->getGridResponse('OjsJournalBundle:Article:index.html.twig');
    }

    /**
     * Displays a form to create a new article entity
     *
     * @return Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $this->throw404IfNotFound($journal);

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $entity = new Article();
        $form = $this->createCreateForm($entity, $journal);

        return $this->render(
            'OjsJournalBundle:Article:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param  Article $entity  The entity
     * @param  Journal $journal
     * @return Form    The form
     */
    private function createCreateForm(Article $entity, Journal $journal)
    {
        $form = $this->createForm(
            new ArticleType(),
            $entity,
            [
                'action' => $this->generateUrl('ojs_journal_article_create', ['journalId' => $journal->getId()]),
                'method' => 'POST',
                'journal' => $journal,
                'default_locale' => $journal->getMandatoryLang()->getCode(),
                'locales' => $journal->getLocaleCodeBag(),
            ]
        );
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Article entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     *
     */
    public function createAction(Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $dispatcher = $this->get('event_dispatcher');
        $entity = new Article();
        $entity = $entity->setJournal($journal);

        $form = $this->createCreateForm($entity, $journal);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCurrentLocale($request->getDefaultLocale());

            $event = new JournalItemEvent($entity);
            $dispatcher->dispatch(ArticleEvents::PRE_CREATE, $event);

            $em->persist($event->getItem());
            $em->flush();

            $this->successFlashBag('article.successful.create');

            $event = new JournalItemEvent($event->getItem());
            $dispatcher->dispatch(ArticleEvents::POST_CREATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            return $this->redirectToRoute(
                'ojs_journal_article_show',
                ['id' => $event->getItem()->getId(), 'journalId' => $journal->getId()]
            );
        }

        return $this->render(
            'OjsJournalBundle:Article:new.html.twig',
            ['entity' => $entity, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays an article entity
     *
     * @param  Article  $article
     * @return Response
     */
    public function showAction(Article $article)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_article'.$article->getId());

        return $this->render('OjsJournalBundle:Article:show.html.twig', ['entity' => $article, 'token' => $token]);
    }

    /**
     * Displays a form to edit an existing article entity
     *
     * @param  Article  $article
     * @return Response
     */
    public function editAction(Article $article)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $editForm = $this->createEditForm($article, $journal)
            ->add('update', 'submit', array('label' => 'u'));

        return $this->render(
            'OjsJournalBundle:Article:edit.html.twig',
            ['entity' => $article, 'form' => $editForm->createView()]
        );
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param  Article $entity  The entity
     * @param  Journal $journal
     * @return Form    The form
     */
    private function createEditForm(Article $entity, Journal $journal)
    {
        $action = $this->generateUrl(
            'ojs_journal_article_update',
            ['id' => $entity->getId(), 'journalId' => $journal->getId()]
        );
        $form = $this->createForm(
            new ArticleType(),
            $entity,
            [
                'action' => $action,
                'method' => 'PUT',
                'journal' => $journal,
                'default_locale' => $journal->getMandatoryLang()->getCode(),
                'locales' => $journal->getLocaleCodeBag(),
            ]
        );

        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
     * @param  Request                   $request
     * @param  Article                   $article
     * @return RedirectResponse|Response
     *
     */
    public function updateAction(Request $request, Article $article)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $dispatcher = $this->get('event_dispatcher');
        $editForm = $this->createEditForm($article, $journal)
            ->add('update', 'submit', array('label' => 'u'));

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $event = new JournalItemEvent($article);
            $dispatcher->dispatch(ArticleEvents::PRE_UPDATE, $event);
            $em->persist($event->getItem());
            $em->flush();
            $this->successFlashBag('successful.update');

            $event = new JournalItemEvent($event->getItem());
            $dispatcher->dispatch(ArticleEvents::POST_UPDATE, $event);

            if ($event->getResponse()) {
                return $event->getResponse();
            }

            return $this->redirect(
                $this->generateUrl(
                    'ojs_journal_article_edit',
                    array('id' => $event->getItem()->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'OjsJournalBundle:Article:edit.html.twig',
            ['entity' => $article, 'form' => $editForm->createView()]
        );
    }

    /**
     * Deletes an article entity
     *
     * @param  Request          $request
     * @param  Article          $article
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Article $article)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_article'.$article->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $event = new JournalItemEvent($article);
        $dispatcher->dispatch(ArticleEvents::PRE_DELETE, $event);

        /** @var Article $article */
        $article = $event->getItem();
        $article->getCitations()->clear();
        $article->getLanguages()->clear();

        $this->get('ojs_core.delete.service')->check($event->getItem());
        $em->remove($event->getItem());
        $em->flush();
        $this->successFlashBag('successful.remove');

        $event = new JournalEvent($journal);
        $dispatcher->dispatch(ArticleEvents::POST_DELETE, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        return $this->redirectToRoute('ojs_journal_article_index', ['journalId' => $journal->getId()]);
    }
}

