<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleAuthor;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\Type\ArticleAuthorType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use APY\DataGridBundle\Grid\Row;

/**
 * ArticleAuthor controller.
 *
 */
class ArticleAuthorController extends Controller
{

    /**
     * Lists all ArticleAuthor entities.
     *
     * @param  Integer  $articleId
     * @return Response
     */
    public function indexAction($articleId, Request $request)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $article = $this->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->find($articleId);

        $this->throw404IfNotFound($article);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('OjsJournalBundle:ArticleAuthor');
        $source->manipulateRow(
            function (Row $row) use ($request) {
                /* @var ArticleAuthor $entity */
                $entity = $row->getEntity();
                if(!is_null($entity)){
                    $entity->getArticle()->setDefaultLocale($request->getDefaultLocale());
                    $row->setField('article', $entity->getArticle()->getTitle());
                }
                return $row;
            }
        );
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
            'ojs_journal_article_author_show',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );
        $rowAction[] = $gridAction->editAction(
            'ojs_journal_article_author_edit',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );
        $rowAction[] = $gridAction->deleteAction(
            'ojs_journal_article_author_delete',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        $data['article'] = $article;

        return $grid->getGridResponse('OjsJournalBundle:ArticleAuthor:index.html.twig', $data);
    }

    /**
     * Creates a new ArticleAuthor entity.
     *
     * @param  Request                   $request
     * @param  $articleId
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, $articleId)
    {
        $journalService = $this->get('ojs.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $article = $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new ArticleAuthor();

        $form = $this->createCreateForm($entity, $journal, $article)
            ->add('create', 'submit', array('label' => 'c'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setArticle($article);
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirect(
                $this->generateUrl(
                    'ojs_journal_article_author_index',
                    array('articleId' => $article->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'article' => $article
            )
        );
    }

    /**
     * @param  ArticleAuthor $entity
     * @param  Journal     $journal
     * @param  Article     $article
     * @return Form
     */
    private function createCreateForm(ArticleAuthor $entity, Journal $journal, Article $article)
    {
        $form = $this->createForm(
            new ArticleAuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_article_author_create',
                    ['journalId' => $journal->getId(), 'articleId' => $article->getId()]
                ),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new ArticleAuthor entity.
     *
     * @param $articleId
     * @return Response
     */
    public function newAction($articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $journalService = $this->get('ojs.journal_service');
        $journal = $journalService->getSelectedJournal();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new ArticleAuthor();
        $entity->setArticle($article);
        $form = $this->createCreateForm($entity, $journal, $article)
            ->add('create', 'submit', array('label' => 'c'));

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'article' => $article,
            )
        );
    }

    /**
     * Finds and displays a ArticleAuthor entity.
     *
     * @param  $id
     * @param  $articleId
     * @return Response
     */
    public function showAction($id, $articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);

        if (is_null($article) || is_null($articleAuthor) || $articleAuthor->getArticle()->getId() !== $article->getId()) {
            $this->throw404IfNotFound($articleAuthor);
        }

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_article_author'.$articleAuthor->getId());

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:show.html.twig',
            array(
                'entity' => $articleAuthor,
                'token' => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing ArticleAuthor entity.
     *
     * @param  $id
     * @param  $articleId
     * @return Response
     */
    public function editAction($id, $articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);

        if (is_null($article) || is_null($articleAuthor) || $articleAuthor->getArticle()->getId() !== $article->getId()) {
            $this->throw404IfNotFound($articleAuthor);
        }

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $editForm = $this->createEditForm($articleAuthor, $journal, $article)
            ->add('save', 'submit', array('label' => 'save'));

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_article_author'.$articleAuthor->getId());

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:edit.html.twig',
            array(
                'entity' => $articleAuthor,
                'edit_form' => $editForm->createView(),
                'token' => $token,
            )
        );
    }

    /**
     * Creates a form to edit a ArticleAuthor entity.
     *
     * @param  ArticleAuthor $entity  The entity
     * @param  Journal     $journal
     * @param  Article     $article
     * @return Form        The form
     */
    private function createEditForm(ArticleAuthor $entity, Journal $journal, Article $article)
    {
        $form = $this->createForm(
            new ArticleAuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'ojs_journal_article_author_update',
                    ['id' => $entity->getId(), 'journalId' => $journal->getId(), 'articleId' => $article->getId()]
                ),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing ArticleAuthor entity.
     *
     * @param  Request                   $request
     * @param  $id
     * @param  $articleId
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id, $articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);

        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        if (is_null($article) || is_null($articleAuthor) || $articleAuthor->getArticle()->getId() !== $article->getId()) {
            $this->throw404IfNotFound($articleAuthor);
        }

        $editForm = $this->createEditForm($articleAuthor, $journal, $article)
            ->add('save', 'submit', array('label' => 'save'));
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->successFlashBag('successful.update');

            return $this->redirect(
                $this->generateUrl(
                    'ojs_journal_article_author_edit',
                    array(
                        'id' => $articleAuthor->getId(),
                        'journalId' => $journal->getId(),
                        'articleId' => $article->getId(),
                    )
                )
            );
        }

        return $this->render(
            'OjsJournalBundle:ArticleAuthor:edit.html.twig',
            array(
                'entity' => $articleAuthor,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a ArticleAuthor entity.
     *
     * @param  Request          $request
     * @param  $id
     * @param  $articleId
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id, $articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('OjsJournalBundle:ArticleAuthor')->find($id);

        if (!$this->isGranted('DELETE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        if (is_null($article) || $articleAuthor == null || $articleAuthor->getArticle()->getId() !== $article->getId()) {
            $this->throw404IfNotFound($articleAuthor);
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_article_author'.$articleAuthor->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('ojs_core.delete.service')->check($articleAuthor);
        $em->remove($articleAuthor);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute(
            'ojs_journal_article_author_index',
            ['articleId' => $article->getId(), 'journalId' => $journal->getId()]
        );
    }
}
