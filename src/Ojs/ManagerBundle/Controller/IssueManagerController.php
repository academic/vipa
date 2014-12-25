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
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$journal) {
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
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$journal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
        return $this->render('OjsManagerBundle:Issue:view.html.twig', array(
                    /**
                     * @todo get articles ordered
                     */
                    'articles' => $issue->getArticles(),
                    'journal' => $journal,
                    'issue' => $issue
        ));
    }

    /**
     * show issue manager edit page
     * @param integer $issueId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws 404
     */
    public function editAction($issueId)
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$journal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        if (!$issue) {
            throw $this->createNotFoundException('Issue not found!');
        }
        return $this->render('OjsManagerBundle:Issue:edit.html.twig', array(
                    /**
                     * @todo get articles ordered
                     */
                    'articles' => $issue->getArticles(),
                    'journal' => $journal,
                    'issue' => $issue
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
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        if (!$journal) {
            return $this->render('::mustselectjournal.html.twig');
        }
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        if (!$article) {
            throw $this->createNotFoundException('Article not found!');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

}
