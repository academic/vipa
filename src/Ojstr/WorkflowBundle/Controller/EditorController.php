<?php

namespace Ojstr\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

/**
 * Editor Workflow Controller
 */
class EditorController extends \Ojstr\Common\Controller\OjsController {

    public function indexAction() {
        return $this->render('OjstrWorkflowBundle:Editor:index.html.twig');
    }

    /**
     * list in-review articles. 
     */
    public function assignedArticlesAction() {
        return $this->listArticles('articles_assigned', 1);
    }

    /**
     * list not assigned articles - waiting
     */
    public function waitingArticlesAction() {
        return $this->listArticles('articles_waiting', 0);
    }

    private function listArticles($view, $status) {
        $articles = $this->getDoctrine()->getManager()
                        ->getRepository("OjstrJournalBundle:Article")->findByStatus($status);
        return $this->render('OjstrWorkflowBundle:Editor:' . $view . '.html.twig', array(
                    'entities' => $articles));
    }

}
