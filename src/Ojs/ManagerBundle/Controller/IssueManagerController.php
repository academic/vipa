<?php

namespace Ojs\ManagerBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
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
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
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
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
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
     * show issue manager arrange issue page
     * @param integer $issueId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws 404
     */
    public function arrangeAction($issueId)
    {
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
        $articles = $em->getRepository('OjsJournalBundle:Article')
                ->getOrderedArticlesByIssue($issue, true);
        $articlesUnissued = $em->getRepository('OjsJournalBundle:Article')
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
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
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
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
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
     *  Move an article's postion in an issue by updating "order" field of Article 
     * @param integer $articleId
     * @param string $upOrDown "up" or "down" to specify the way of movement
     * @throws 404
     */
    public function moveAction($articleId, $upOrDown = 'up')
    {
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        if (!$article) {
            throw $this->createNotFoundException('Article not found!');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    public function addArticleAction(Request $request)
    {
        if (!$journal = $this->get("ojs.journal_service")->getSelectedJournal()) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $articleId = $request->get('unissued_article_id');
        $issueId = $request->get('issue_id');
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);

        if (!$article) {
            throw $this->createNotFoundException('Article not found!');
        }
        $article->setIssueId($issueId);
        $em->persist($article);
        $em->flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

}
