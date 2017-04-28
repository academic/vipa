<?php

namespace Vipa\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use GuzzleHttp\Exception\RequestException;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Article;
use Vipa\JournalBundle\Entity\ArticleAuthor;
use Vipa\JournalBundle\Entity\Author;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Form\Type\ArticleAuthorType;
use Vipa\JournalBundle\Form\Type\ArticleAddAuthorType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();

        $article = $this->getDoctrine()
            ->getRepository('VipaJournalBundle:Article')
            ->find($articleId);

        $this->throw404IfNotFound($article);

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $source = new Entity('VipaJournalBundle:ArticleAuthor');
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
            'vipa_journal_article_author_show',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );
        $rowAction[] = $gridAction->editAction(
            'vipa_journal_article_author_edit',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );
        $rowAction[] = $gridAction->deleteAction(
            'vipa_journal_article_author_delete',
            ['id', 'journalId' => $journal->getId(), 'articleId' => $articleId]
        );

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        $data['article'] = $article;

        return $grid->getGridResponse('VipaJournalBundle:ArticleAuthor:index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param  $articleId
     * @return Response
     */
    public function authorSortAction(Request $request, $articleId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $authors = $em->getRepository(ArticleAuthor::class)->findBy(['article' => $articleId]);
        usort($authors, function($a, $b){
            return $a->getAuthorOrder() > $b->getAuthorOrder();
        });

        $sortData = [];
        foreach ($authors as $author){
            $sortData[$author->getId()] = $author->getAuthorOrder();
        }

        if($request->getMethod() == 'POST' && $request->request->has('sortData')){
            $sortData = json_decode($request->request->get('sortData'));
            foreach ($sortData as $authorId => $order){
                foreach ($authors as $author){
                    if($author->getId() == $authorId){
                        $author->setAuthorOrder($order);
                        $em->persist($author);
                    }
                }
            }
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('vipa_journal_article_author_sort', [
                'journalId' => $journal->getId(),
                'articleId' => $articleId
            ]);
        }

        return $this->render('VipaJournalBundle:ArticleAuthor:author_sort.html.twig', [
                'authors' => $authors,
                'jsonSortData' => json_encode($sortData),
                'articleId' => $articleId
            ]
        );
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
        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $article = $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
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
                    'vipa_journal_article_author_index',
                    array('articleId' => $article->getId(), 'journalId' => $journal->getId())
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:ArticleAuthor:new.html.twig',
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
                    'vipa_journal_article_author_create',
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
        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new ArticleAuthor();
        $entity->setArticle($article);
        $form = $this->createCreateForm($entity, $journal, $article)
            ->add('create', 'submit', array('label' => 'c'));

        return $this->render(
            'VipaJournalBundle:ArticleAuthor:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'article' => $article,
            )
        );
    }

    /**
     * Displays a form to create a new ArticleAuthor entity.
     *
     * @param Request $request
     * @param $articleId
     * @return Response
     */
    public function addAction(Request $request, $articleId)
    {

        $journalService = $this->get('vipa.journal_service');
        $journal = $journalService->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $article = $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        if (!$this->isGranted('EDIT', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $entity = new ArticleAuthor();
        $author = new Author();

        $form = $this->createAddForm($entity, $journal, $article)
            ->add('create', 'submit', array('label' => 'c'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $author->setFirstName($entity->getAuthor()->getFirstName());
            $author->setLastName($entity->getAuthor()->getLastName());
            $author->setEmail($entity->getAuthor()->getEmail());
            $author->setTitle($entity->getAuthor()->getTitle());
            $author->setUser($entity->getAuthor()->getUser());
            
            $entity->setAuthor($author);
            $entity->setArticle($article);
            $em->persist($author);
            $em->persist($entity);
            $em->flush();

            $this->successFlashBag('successful.create');

            return $this->redirectToRoute(
                    'vipa_journal_article_author_edit',
                    array('articleId' => $article->getId(), 'journalId' => $journal->getId(), 'id' => $author->getId())
            );
        }

        return $this->render(
            'VipaJournalBundle:ArticleAuthor:add.html.twig',
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
    private function createAddForm(ArticleAuthor $entity, Journal $journal, Article $article)
    {
        $form = $this->createForm(
            new ArticleAddAuthorType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'vipa_journal_article_author_add',
                    ['journalId' => $journal->getId(), 'articleId' => $article->getId()]
                ),
                'method' => 'POST',
                'journalId' => $journal->getId()
            )
        );

        return $form;
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
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('VipaJournalBundle:ArticleAuthor')->find($id);

        if (is_null($article) || is_null($articleAuthor) || $articleAuthor->getArticle()->getId() !== $article->getId()) {
            $this->throw404IfNotFound($articleAuthor);
        }

        if (!$this->isGranted('VIEW', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('vipa_journal_article_author'.$articleAuthor->getId());

        return $this->render(
            'VipaJournalBundle:ArticleAuthor:show.html.twig',
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
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('VipaJournalBundle:ArticleAuthor')->find($id);

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
            ->refreshToken('vipa_journal_article_author'.$articleAuthor->getId());

        return $this->render(
            'VipaJournalBundle:ArticleAuthor:edit.html.twig',
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
                    'vipa_journal_article_author_update',
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
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('VipaJournalBundle:ArticleAuthor')->find($id);

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
                    'vipa_journal_article_author_edit',
                    array(
                        'id' => $articleAuthor->getId(),
                        'journalId' => $journal->getId(),
                        'articleId' => $article->getId(),
                    )
                )
            );
        }

        return $this->render(
            'VipaJournalBundle:ArticleAuthor:edit.html.twig',
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
        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        $article = $em->getRepository('VipaJournalBundle:Article')->find($articleId);
        $articleAuthor = $em->getRepository('VipaJournalBundle:ArticleAuthor')->find($id);

        if (!$this->isGranted('DELETE', $journal, 'articles')) {
            throw new AccessDeniedException("You not authorized for this page!");
        }

        if (is_null($article) || $articleAuthor == null || $articleAuthor->getArticle()->getId() !== $article->getId()) {
            $this->throw404IfNotFound($articleAuthor);
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('vipa_journal_article_author'.$articleAuthor->getId());
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $this->get('vipa_core.delete.service')->check($articleAuthor);
        $em->remove($articleAuthor);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute(
            'vipa_journal_article_author_index',
            ['articleId' => $article->getId(), 'journalId' => $journal->getId()]
        );
    }



    /**
     * Search journal based users
     *
     * @param Request $request
     * @return Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException|static
     */
    public function getAuthorBasedJournalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $journal = $this->get('vipa.journal_service')->getSelectedJournal();
        if (!$journal) {
            return $this->createNotFoundException();
        }

        $defaultLimit = 20;
        $limit = ($request->get('page_limit') && $defaultLimit >= $request->get('page_limit')) ?
            $request->get('page_limit') :
            $defaultLimit;

        $journalAuthors = $em->getRepository('VipaJournalBundle:Author')->searchJournalAuthor(
            $request->get('q'),
            $journal,
            $limit
        );
        $data = [];
        if (count($journalAuthors) > 0) {
            foreach ($journalAuthors as $journalAuthor) {
                $data[] = [
                    'id' => $journalAuthor->getId(),
                    'text' => (string) $journalAuthor->getFullName().' ('.$journalAuthor->getEmail().')',
                ];
            }
        }

        return JsonResponse::create($data);
    }

}
