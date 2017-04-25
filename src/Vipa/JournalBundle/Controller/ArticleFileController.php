<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\CoreBundle\Params\ArticleFileParams;
use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Entity\ArticleFile;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Form\Type\ArticleFileType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * ArticleFile controller.
 */
class ArticleFileController extends Controller
{
    /**
     * Lists all ArticleFile entities.
     * @param   Integer $articleId
     * @return  Response
     */
    public function indexAction($articleId)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        $article = $this->getDoctrine()
                        ->getRepository('VipaJournalBundle:Article')
                        ->find($articleId);

        $this->throw404IfNotFound($article);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('VipaJournalBundle:ArticleFile');
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $qb) use ($article, $tableAlias) {
                return $qb
                    ->where($tableAlias.'.article = :article')
                    ->setParameter('article', $article);
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction(
            'vipa_journal_article_file_show',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );
        $rowAction[] = $gridAction->editAction(
            'vipa_journal_article_file_edit',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );
        $rowAction[] = $gridAction->deleteAction(
            'vipa_journal_article_file_delete',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        $data['article'] = $article;

        return $grid->getGridResponse('VipaJournalBundle:ArticleFile:index.html.twig', $data);
    }

    /**
     * Creates a new ArticleFile entity.
     * @param  Request $request
     * @param  integer $articleId
     * @return RedirectResponse|Response
     *
     */
    public function createAction(Request $request, $articleId)
    {
        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        /** @var Article $article */
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);

        $entity = new ArticleFile();
        $form = $this->createCreateForm($entity, $journal, $article)
                     ->add('create', 'submit', ['label' => 'c']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setArticle($article);
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirect(
                $this->generateUrl(
                    'vipa_journal_article_file_index',
                    ['articleId' => $article->getId(), 'journalId' => $journal->getId()]
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:ArticleFile:new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * Creates a form to create a ArticleFile entity.
     * @param   ArticleFile $entity
     * @param   Journal $journal
     * @param   Article $article
     * @return Form
     */
    private function createCreateForm(ArticleFile $entity, Journal $journal, Article $article)
    {
        $form = $this->createForm(
            new ArticleFileType(),
            $entity,
            [
                'action'  => $this->generateUrl(
                    'vipa_journal_article_file_create',
                    ['journalId' => $journal->getId(), 'articleId' => $article->getId()]
                ),
                'method'  => 'POST',
            ]
        );

        return $form;
    }

    /**
     * Displays a form to create a new ArticleFile entity.
     * @param  integer $articleId
     * @return Response
     */
    public function newAction($articleId)
    {
        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        /** @var Article $article */
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);

        $entity = new ArticleFile();
        $entity->setArticle($article);
        $form = $this->createCreateForm($entity, $journal, $article)
                     ->add('create', 'submit', ['label' => 'c']);

        return $this->render(
            'VipaJournalBundle:ArticleFile:new.html.twig',
            [
                'entity'  => $entity,
                'form'    => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Finds and displays a ArticleFile entity.
     * @param integer $id
     * @param integer $articleId
     * @return Response
     */
    public function showAction($id, $articleId)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:ArticleFile')->findOneBy(
            [
                'article' => $article,
                'id'      => $id,
            ]
        );

        $this->throw404IfNotFound($entity);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $type = ArticleFileParams::fileType($entity->getType());

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_article_file'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:ArticleFile:show.html.twig',
            [
                'entity' => $entity,
                'type'   => $type,
                'token'  => $token,
            ]
        );
    }

    /**
     * Displays a form to edit an existing ArticleFile entity.
     * @param integer $id
     * @param integer $articleId
     * @return Response
     */
    public function editAction($id, $articleId)
    {
        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        /** @var Article $article */
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:ArticleFile')->findOneBy(
            [
                'article' => $article,
                'id'      => $id,
            ]
        );

        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal, $article)
                         ->add('save', 'submit', ['label' => 'save']);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_article_file'.$entity->getId());

        return $this->render(
            'VipaJournalBundle:ArticleFile:edit.html.twig',
            [
                'entity'    => $entity,
                'edit_form' => $editForm->createView(),
                'token'     => $token,
            ]
        );
    }

    /**
     * Creates a form to edit a ArticleFile entity.
     * @param ArticleFile $entity The entity
     * @param Journal $journal
     * @param Article $article
     * @return Form The form
     */
    private function createEditForm(ArticleFile $entity, Journal $journal, Article $article)
    {
        $form = $this->createForm(
            new ArticleFileType(),
            $entity,
            [
                'action'  => $this->generateUrl(
                    'vipa_journal_article_file_update',
                    ['id' => $entity->getId(), 'journalId' => $journal->getId(), 'articleId' => $article->getId()]
                ),
                'method'  => 'PUT',
            ]
        );

        return $form;
    }

    /**
     * Edits an existing ArticleFile entity.
     * @param  Request $request
     * @param integer $id
     * @param integer $articleId
     * @return RedirectResponse|Response
     *
     */
    public function updateAction(Request $request, $id, $articleId)
    {
        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        /** @var Article $article */
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:ArticleFile')->findOneBy(
            [
                'article' => $article,
                'id'      => $id,
            ]
        );
        $this->throw404IfNotFound($entity);

        $editForm = $this->createEditForm($entity, $journal, $article)
                         ->add('save', 'submit', ['label' => 'save']);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl(
                    'vipa_journal_article_file_edit',
                    ['id' => $id, 'journalId' => $journal->getId(), 'articleId' => $article->getId()]
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:ArticleFile:edit.html.twig',
            [
                'entity'    => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Deletes a ArticleFile entity.
     * @param  Request $request
     * @param  integer $id
     * @param  integer $articleId
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id, $articleId)
    {
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        /** @var Article $article */
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('VipaJournalBundle:ArticleFile')->findOneBy(
            [
                'article' => $article,
                'id'      => $id,
            ]
        );

        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_article_file'.$entity->getId());

        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($entity);
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute(
            'vipa_journal_article_file_index',
            ['articleId' => $entity->getArticle()->getId(), 'journalId' => $journal->getId()]
        );
    }
}
