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
        $entities = $em->getRepository('OjsJournalBundle:Issue')->findAll();

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

}
