<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Form\IssueType;

/**
 * Issue manager controller.
 *
 */
class IssueManagerController extends Controller
{

    /**
     * Lists all Issue entities for selected journal.
     *
     */
    public function indexAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsJournalBundle:Issue')->findByJournalId($journal->getId());

        return $this->render('OjsManagerBundle:Issue:index.html.twig', array(
                    'entities' => $entities,
                    'journal' => $journal
        ));
    }

    /**
     * show issue manager view page
     * @param integer $issueId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws 404
     */
    public function viewAction($issueId)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
        $articles = $em->getRepository('OjsJournalBundle:Article')->getOrderedArticlesByIssue($issue, true);
        return $this->render('OjsManagerBundle:Issue:view.html.twig', array(
                    'articles' => $articles,
                    'journal' => $journal,
                    'issue' => $issue
        ));
    }

    /**
     * show issue manager arrange issue page , arrange and update
     * @param Request $request
     * @param integer $issueId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws 404
     */
    public function arrangeAction(Request $request, $issueId)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $doctrine = $this->getDoctrine();
        $issue = $doctrine->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }

        if ($request->isMethod('POST')) {
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

        return $this->render('OjsManagerBundle:Issue:arrange.html.twig', array(
                    'articles' => $articles,
                    'journal' => $journal,
                    'issue' => $issue,
                    'articlesUnissued' => $articlesUnissued
        ));
    }

    /**
     * "create new issue" page customized for editors|journal managers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $issue = new Issue();
        $form = $this->createForm(new IssueType(), $issue, array(
            'action' => $this->generateUrl('issue_manager_issue_new'),
            'method' => 'POST',
            'user' => $this->getUser()
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            return $this->redirect(
                            $this->generateUrl(
                                    'issue_manager_issue_view', array('issueId' => $issue->getId()
                                    )
            ));
        }


        return $this->render('OjsJournalBundle:Issue:new.html.twig', array(
                    'journal' => $journal,
                    'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $issueId)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        $form = $this->createForm(new IssueType(), $issue, array(
            'action' => $this->generateUrl('issue_manager_issue_edit', array('issueId' => $issueId)),
            'method' => 'PUT',
            'user' => $this->getUser()
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();
            return $this->redirect(
                            $this->generateUrl(
                                    'issue_manager_issue_view', array('issueId' => $issue->getId()
                                    )
            ));
        }
        return $this->render('OjsJournalBundle:Issue:edit.html.twig', array(
                    'journal' => $journal,
                    'entity' => $issue,
                    'edit_form' => $form->createView()
        ));
    }

    /**
     * Move an article's postion UP in an issue by updating "order" field of Article 
     * @param integer $id issue id
     * @param integer $articleId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws 404
     */
    public function moveArticleUpAction($id, $articleId)
    {
        $this->checkIssue($id);
        return $this->moveArticle($articleId, 1);
    }

    /**
     * Move an article's postion DOWN in an issue by updating "order" field of Article 
     * @param integer $id issue id
     * @param integer $articleId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws 404
     */
    public function moveArticleDownAction($id, $articleId)
    {
        $this->checkIssue($id);
        return $this->moveArticle($articleId, -1);
    }

    /**
     *  Move an article's postion in an issue by updating "order" field of Article 
     * @param integer $articleId
     * @param string $direction "1" or "-1" to specify the way of movement
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws 404
     */
    public function moveArticleAction($articleId, $direction = 1)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getmanager();
        $article = $doctrine->getRepository('OjsJournalBundle:Article')->find($articleId);
        /* @var $article Ojs\JournalBundle\Entity\Article */
        $this->throw404IfNotFound($article);
        $currentPosition = $article->getPosition();
        $nextPosition = 0;
        if ($direction > 0) {
            $nextPosition = $currentPosition + $direction;
        } else {
            $nextPosition = ($currentPosition - $direction ) < 0 ? 0 : ($currentPosition - $direction);
        }
        $article->setPosition($nextPosition);
        $em->persist($article);
        $em->flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * add article to this issue
     * @param integer $id
     * @param integer $articleId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function addArticleAction($id, $articleId)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $this->checkIssue($id);
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);
        $article->setIssueId($id);
        $em->persist($article);
        $em->flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Remove article fro this issue
     * @param integer $id Issue id
     * @param integer $articleId Article id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function removeArticleAction($id, $articleId)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $this->checkIssue($id);
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        $this->throw404IfNotFound($article);
        $article->setIssueId(null);
        $em->persist($article);
        $em->flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Check if issue exists. If not throw exception. If so return issue
     * @param integer $id
     * @return Issue
     * @throws \Exception
     */
    private function checkIssue($id)
    {
        $issue = $this->getDoctrine()->getRepository('OjsJournalBundle:Issue')->find($id);
        $this->throw404IfNotFound($issue);
        return $issue;
    }

}
