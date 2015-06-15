<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleRepository;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\JournalSection;
use Ojs\JournalBundle\Form\Type\IssueType;
use Ojs\UserBundle\Entity\User;
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
     */
    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        /** @var User $user */
        $user = $this->getUser();

        if (!$this->isGranted('VIEW', $journal, 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issues!");
        }
        $source = new Entity('OjsJournalBundle:Issue');
        //if user is not admin show only selected journal
        if (!$user->isAdmin()) {
            $ta = $source->getTableAlias();
            $source->manipulateQuery(
                function (QueryBuilder $query) use ($ta, $journal) {
                    $query->andWhere($ta.'.journalId = :journal_id')
                        ->setParameter('journal_id', $journal->getId());
                }
            );
        }
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = $gridAction->showAction($user->isAdmin() ? 'issue_show' : 'issue_manager_issue_view', 'id');
        if ($this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            $rowAction[] = $gridAction->editAction('issue_edit', 'id');
        }
        if ($this->isGranted('DELETE', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            $rowAction[] = $gridAction->deleteAction('issue_delete', 'id');
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
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for create a issue on this journal!");
        }
        $entity = new Issue();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setCoverOptions(json_encode($cover));
            }

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('issue_show', ['id' => $entity->getId()]);
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
     * @param Issue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Issue $entity)
    {
        $user = $this->getUser();
        $form = $this->createForm(
            new IssueType(),
            $entity,
            array(
                'action' => $this->generateUrl('issue_create'),
                'method' => 'POST',
                'tagEndPoint' => $this->generateUrl('api_get_tags'),
                'user' => $user,
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Issue entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for create a issue on this journal!");
        }
        $entity = new Issue();
        $form = $this->createCreateForm($entity);

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
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        if (!$this->isGranted('VIEW', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issue!");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render(
            'OjsJournalBundle:Issue:show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Issue entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        if (!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $em = $this->getDoctrine()->getManager();
        /** @var Issue $entity */
        $entity = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsJournalBundle:Issue:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Issue entity.
     * @param  Issue                        $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Issue $entity)
    {
        $user = $this->getUser();
        $form = $this->createForm(
            new IssueType(),
            $entity,
            array(
                'action' => $this->generateUrl('issue_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'tagEndPoint' => $this->generateUrl('api_get_tags'),
                'user' => $user,
            )
        );

        return $form;
    }

    /**
     * Edits an existing Issue entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        if (!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $em = $this->getDoctrine()->getManager();
        /** @var Issue $entity */
        $entity = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $header = $request->request->get('header');
            $cover = $request->request->get('cover');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            if ($cover) {
                $entity->setCoverOptions(json_encode($cover));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('issue_edit', ['id' => $id]);
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
     * @param  Request          $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->isGranted('DELETE', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for delete this journal's issue!");
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('issue'.$id);
        if ($token != $request->get('_token')) {
            throw new TokenNotFoundException("Token Not Found!");
        }
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('issue');
    }

    /**
     * show issue manager view page
     * @param  integer               $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function viewAction($id)
    {
        if (!$this->isGranted('VIEW', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issue!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        /** @var Issue $issue */
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
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
     * @param  Request               $request
     * @param  integer               $issueId
     * @return Response
     * @throws NotFoundHttpException
     */
    public function arrangeAction(Request $request, $issueId)
    {
        if (!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        /** @var Issue $issue */
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
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
                $em->flush();
            }
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
     * @param  Request               $request
     * @param $id
     * @param $articleId
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function addArticleAction(Request $request, $id, $articleId)
    {
        if (!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $selectedSection = $request->get('section', null);
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $this->checkIssue($id);
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        /** @var JournalSection $section */
        $section = $em->getRepository('OjsJournalBundle:JournalSection')->find($selectedSection);
        /** @var Issue $issue */
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($article);
        $article->setIssueId($id);
        if ($section) {
            $sections = $issue->getSections();
            if (!$sections->contains($section)) {
                $issue->addSection($section);
                $em->persist($issue);
            }
            $article->setSectionId($section->getId());
            $article->setSection($section);
        }
        $em->persist($article);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Remove article fro this issue
     * @param  Request               $request
     * @param $id
     * @param $articleId
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function removeArticleAction(Request $request, $id, $articleId)
    {
        if (!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $referrer = $request->headers->get('referer');
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $this->checkIssue($id);
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);
        $article->setIssueId(null);
        $em->persist($article);
        $em->flush();
        $this->successFlashBag('Successfully removed.');

        return $this->redirect($referrer);
    }

    /**
     * Check if issue exists. If not throw exception. If so return issue
     * @param  integer               $id
     * @return Issue
     * @throws NotFoundHttpException
     */
    private function checkIssue($id)
    {
        $issue = $this->getDoctrine()->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($issue);

        return $issue;
    }
}
