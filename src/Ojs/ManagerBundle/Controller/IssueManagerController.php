<?php

namespace Ojs\ManagerBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Form\IssueType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Issue manager controller.
 *
 */
class IssueManagerController extends Controller
{

    /**
     * Lists all Issue entities for selected journal.
     * @return Response
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issues!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $source = new Entity('OjsJournalBundle:Issue');
        $ta = $source->getTableAlias();
        $source->manipulateQuery(
            function (QueryBuilder $query) use ($ta, $journal)
            {
                $query->andWhere($ta . '.journalId = :journal_id')
                ->setParameter('journal_id', $journal->getId());
            }
        );
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        $rowAction[] = ActionHelper::showAction('issue_manager_issue_view', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsManagerBundle:Issue:index.html.twig', $data);
    }

    /**
     * show issue manager view page
     * @param  integer   $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function viewAction($id)
    {
        if(!$this->isGranted('VIEW', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for view this journal's issue!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($id);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
        $articles = $em->getRepository('OjsJournalBundle:Article')->getOrderedArticlesByIssue($issue, true);

        return $this->render('OjsManagerBundle:Issue:view.html.twig', array(
                    'articles' => $articles,
                    'journal' => $journal,
                    'issue' => $issue,
        ));
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
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $doctrine = $this->getDoctrine();
        $issue = $doctrine->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }

        if ($request->isMethod('POST') && $request->get('articleId')) {
            $em = $doctrine->getManager();
            // update with with new values
            $articleIds = $request->get('articleId');
            $orders = $request->get('order');
            $firstPages = $request->get('firstPage');
            $lastPages = $request->get('lastPage');

            foreach ($articleIds as $i => $articleId) {
                $article = $doctrine->getRepository('OjsJournalBundle:Article')
                        ->find($articleId);
                $this->throw404IfNotFound($article);
                $article->setOrderNum($orders[$i]);
                $article->setFirstPage($firstPages[$i]);
                $article->setLastPage($lastPages[$i]);
                $em->persist($article);
                $em->flush();
            }
        }

        $articles = $doctrine->getRepository('OjsJournalBundle:Article')
                ->getOrderedArticlesByIssue($issue, true);
        $articlesUnissued = $doctrine->getRepository('OjsJournalBundle:Article')
                ->getArticlesUnissued();
        $sections = $journal->getSections();

        $data = ['articles' => $articles,
            'journal' => $journal,
            'issue' => $issue,
            'sections' => $sections,
            'articlesUnissued' => $articlesUnissued, ];

        return $this->render('OjsManagerBundle:Issue:arrange.html.twig', $data);
    }

    /**
     * "create new issue" page customized for editors|journal managers
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        if(!$this->isGranted('CREATE', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for create a issue on this journal!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $issue = new Issue();
        $form = $this->createForm(new IssueType(), $issue, array(
            'action' => $this->generateUrl('issue_manager_issue_new'),
            'method' => 'POST',
            'user' => $this->getUser(),
            'journal' => $journal,
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            $this->successFlashBag('Successfully created.');

            return $this->redirectToRoute('issue_manager_issue_view', [
                    'issueId' => $issue->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:Issue:new.html.twig', array(
                    'journal' => $journal,
                    'form' => $form->createView(),
                    'entity' => $issue,
        ));
    }

    /**
     * @param  Request                   $request
     * @param $issueId
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $issueId)
    {
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        $form = $this->createForm(new IssueType(), $issue, array(
            'action' => $this->generateUrl('issue_manager_issue_edit', array('issueId' => $issueId)),
            'method' => 'PUT',
            'user' => $this->getUser(),
            'journal' => $journal,
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            $this->successFlashBag('Successfully updated.');

            return $this->redirectToRoute('issue_manager_issue_view', [
                    'issueId' => $issue->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:Issue:edit.html.twig', array(
                    'journal' => $journal,
                    'entity' => $issue,
                    'edit_form' => $form->createView(),
        ));
    }

    /**
     * Move an article's postion UP in an issue by updating "order" field of Article
     * @param  integer               $id        issue id
     * @param  integer               $articleId
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function moveArticleUpAction($id, $articleId)
    {
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $this->checkIssue($id);

        return $this->moveArticleAction($articleId, 1);
    }

    /**
     * Move an article's position DOWN in an issue by updating "order" field of Article
     * @param  integer               $id        issue id
     * @param  integer               $articleId
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function moveArticleDownAction($id, $articleId)
    {
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $this->checkIssue($id);

        return $this->moveArticleAction($articleId, -1);
    }

    /**
     *  Move an article's position in an issue by updating "order" field of Article
     * @param  integer               $articleId
     * @param  integer               $direction "1" or "-1" to specify the way of movement
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function moveArticleAction($articleId, $direction = 1)
    {
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getmanager();
        $article = $doctrine->getRepository('OjsJournalBundle:Article')->find($articleId);
        /* @var $article Article */
        $this->throw404IfNotFound($article);
        // TODO : missing position getter setter
        $currentPosition = $article->getPosition();
        $nextPosition = 0;
        if ($direction > 0) {
            $nextPosition = $currentPosition + $direction;
        } else {
            $nextPosition = ($currentPosition - $direction) < 0 ? 0 : ($currentPosition - $direction);
        }
        $article->setPosition($nextPosition);
        $em->persist($article);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * add article to this issue
     * @param  Request               $r
     * @param $id
     * @param $articleId
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function addArticleAction(Request $r, $id, $articleId)
    {
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
            throw new AccessDeniedException("You are not authorized for edit this journal's issue!");
        }
        $selectedSection = $r->get('section', null);
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $this->checkIssue($id);
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
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

        return $this->redirect($this->getRequest()->headers->get('referer'));
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
        if(!$this->isGranted('EDIT', $this->get('ojs.journal_service')->getSelectedJournal(), 'issues')) {
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
