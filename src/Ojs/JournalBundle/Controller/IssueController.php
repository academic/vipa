<?php

namespace Ojs\JournalBundle\Controller;

use Doctrine\ORM\Query;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\JournalSection;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Form\Type\IssueType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

/**
 * Issue controller.
 *
 */
class IssueController extends Controller
{
    /**
     * Lists all Issue entities.
     *
     * @return  Response
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issues!");
        }

        $source = new Entity('OjsJournalBundle:Issue');
        $source->addHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');

        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($ta, $journal) {
                $query->andWhere($ta . '.journalId = :journal_id')
                    ->setParameter('journal_id', $journal->getId());
            }
        );

        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction('ojs_journal_issue_show', ['id', 'journalId' => $journal->getId()]);

        $articleAction = new RowAction('<i class="fa fa-file-text"></i>', 'ojs_journal_issue_view');
        $articleAction->setRouteParameters(['journalId' => $journal->getId(), 'id']);
        $articleAction->setAttributes(
            [
                'class' => 'btn btn-success btn-xs  ',
                'data-toggle' => 'tooltip',
                'title' => $this->get('translator')->trans("Articles"),
            ]
        );

        $rowAction[] = $articleAction;
        if ($this->isGranted('EDIT', $journal, 'issues')) {
            $rowAction[] = $gridAction->editAction('ojs_journal_issue_edit', ['id', 'journalId' => $journal->getId()]);
        }

        if ($this->isGranted('DELETE', $journal, 'issues')) {
            $rowAction[] = $gridAction->deleteAction('ojs_journal_issue_delete', ['id', 'journalId' => $journal->getId()]);
        }

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Issue:index.html.twig', $data);
    }

    /**
     * Creates a new Issue entity.
     *
     * @param   Request $request
     * @return  RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /* @var Journal $journal */

        $em = $this->getDoctrine()->getManager();
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for create a issue on this journal!");
        }

        $entity = new Issue();
        $form = $this->createCreateForm($entity, $journal->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setJournal($journal);

            $entity->setTranslatableLocale($request->getDefaultLocale());
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('ojs_journal_issue_show', ['id' => $entity->getId(), 'journalId' => $journal->getId()]);
        }

        return $this->render(
            'OjsJournalBundle:Issue:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Issue entity.
     *
     * @param   Issue   $entity     The entity
     * @param   integer $journalId
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Issue $entity, $journalId)
    {
        $form = $this->createForm(
            new IssueType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_issue_create', ['journalId' => $journalId]),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Issue entity.
     *
     * @return  Response
     */
    public function newAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('CREATE', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for create a issue on this journal!");
        }

        $entity = new Issue();
        $form = $this->createCreateForm($entity, $journal->getId());

        return $this->render(
            'OjsJournalBundle:Issue:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Issue entity.
     *
     * @param   int $id
     * @return Response
     */
    public function showAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issue!");
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($entity);

        $token = $this
            ->get('security.csrf.token_manager')
            ->refreshToken('ojs_journal_issue'.$entity->getId());

        return $this->render(
            'OjsJournalBundle:Issue:show.html.twig',
            array(
                'entity' => $entity,
                'token'  => $token,
            )
        );
    }

    /**
     * Displays a form to edit an existing Issue entity.
     *
     * @param   int $id
     * @return  Response
     */
    public function editAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }

        $em = $this->getDoctrine()->getManager();
        /** @var Issue $entity */
        $entity = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity, $journal->getId());


        return $this->render(
            'OjsJournalBundle:Issue:edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ]);
    }

    /**
     * Creates a form to edit a Issue entity.
     * @param   Issue $entity The entity
     * @param   integer $journalId
     * @return  \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Issue $entity, $journalId)
    {
        $form = $this->createForm(
            new IssueType(),
            $entity,
            array(
                'action' => $this->generateUrl('ojs_journal_issue_update',
                    ['id' => $entity->getId(), 'journalId' => $journalId]),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Issue entity.
     *
     * @param   Request $request
     * @param   $id
     * @return  RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }

        $em = $this->getDoctrine()->getManager();
        /** @var Issue $entity */
        $entity = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity, $journal->getId());
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_journal_issue_edit', ['journalId' => $entity->getJournalId(),'id' => $id]);
        }

        return $this->render(
            'OjsJournalBundle:Issue:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * @param   Request $request
     * @param   int     $id
     * @return  RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('DELETE', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for delete this journal's issue!");
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('ojs_journal_issue' . $id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_journal_issue_index', ['journalId' => $journal->getId()]);
    }

    /**
     * show issue manager view page
     * @param   integer $id
     * @return  Response
     * @throws  NotFoundHttpException
     */
    public function viewAction($id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issue!");
        }
        $em = $this->getDoctrine()->getManager();
        /** @var Issue $issue */
        $issue = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );
        $this->throw404IfNotFound($issue);
        /** @var ArticleRepository $repo */
        $repo = $em->getRepository('OjsJournalBundle:Article');
        $articles = $repo->getOrderedArticlesByIssue($issue, true);

        return $this->render(
            'OjsJournalBundle:Issue:view.html.twig',
            array(
                'articles' => $articles,
                'journal' => $journal,
                'issue' => $issue,
            )
        );
    }

    /**
     * show issue manager arrange issue page , arrange and update
     * @param   Request $request
     * @param   integer $id
     * @return  Response
     * @throws  NotFoundHttpException
     */
    public function arrangeAction(Request $request, $id)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }

        $em = $this->getDoctrine()->getManager();

        /** @var Issue $issue */
        $issue = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($issue);

        /** @var ArticleRepository $articleRepo */
        $articleRepo = $em->getRepository('OjsJournalBundle:Article');
        if ($request->isMethod('POST') && $request->get('articleId')) {
            // update with with new values
            $articleIds = $request->get('articleId');
            $orders = $request->get('order');
            $firstPages = $request->get('firstPage');
            $lastPages = $request->get('lastPage');

            foreach ($articleIds as $i => $articleId) {
                $article = $articleRepo->find($articleId);
                $this->throw404IfNotFound($article);
                $article->setOrderNum($orders[$i]);
                $article->setFirstPage($firstPages[$i]);
                $article->setLastPage($lastPages[$i]);
                $em->persist($article);
            }
            $em->flush();

        }

        $articles = $articleRepo->getOrderedArticlesByIssue($issue, true);
        $articlesUnissued = $articleRepo->getArticlesUnissued();
        $sections = $journal->getSections();

        $data = [
            'articles' => $articles,
            'journal' => $journal,
            'issue' => $issue,
            'sections' => $sections,
            'articlesUnissued' => $articlesUnissued,
        ];

        return $this->render('OjsJournalBundle:Issue:arrange.html.twig', $data);
    }

    /**
     * add article to this issue
     * @param   Request $request
     * @param   integer $id
     * @param   integer $articleId
     * @return  RedirectResponse
     * @throws  NotFoundHttpException
     */
    public function addArticleAction(Request $request, $id, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }

        $em = $this->getDoctrine()->getManager();

        /** @var Issue $issue */
        $issue = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($issue);

        $selectedSection = $request->get('section', null);

        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find(
            array(
                'id' => $articleId,
                'journal' => $journal,
            )
        );

        $this->throw404IfNotFound($article);
        $section = null;

        if ($selectedSection) {
            /** @var JournalSection $section */
            $section = $em->getRepository('OjsJournalBundle:JournalSection')->find(
                array('id' => $selectedSection, 'journal' => $journal)
            );
            $this->throw404IfNotFound($section);
        }

        $article->setIssueId($id);

        if ($section) {
            $sections = $issue->getSections();
            if (!$sections->contains($section)) {
                $issue->addSection($section);
                $em->persist($issue);
            }
            $article->setSection($section);
            $article->setSection($section);
        }

        $em->persist($article);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Remove article fro this issue
     * @param   Request $request
     * @param   integer $id
     * @param   integer $articleId
     * @return  RedirectResponse
     * @throws  NotFoundHttpException
     */
    public function removeArticleAction(Request $request, $id, $articleId)
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }

        $referrer = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();

        $issue = $em->getRepository('OjsJournalBundle:Issue')->findOneBy(
            array('id' => $id, 'journal' => $journal)
        );

        $this->throw404IfNotFound($issue);
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->findOneBy(
            array('id' => $articleId, 'journal' => $journal)
        );

        $this->throw404IfNotFound($article);

        $article->setIssue(null);
        $em->persist($article);
        $em->flush();
        $this->successFlashBag('Successfully removed.');

        return $this->redirect($referrer);
    }
}
