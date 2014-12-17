<?php

namespace Ojs\WorkflowBundle\Controller;

/**
 * Controller for journal's all users 
 * actions will check roles in their logic
 */
class ManagerController extends \Ojs\Common\Controller\OjsController
{

    /**
     * Preview article's current data with given object id 
     * @param string $id
     */
    public function articleAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->get('doctrine')->getManager();
 
        $articleStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->find($id);
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleStep->getArticleId());
        return $this->render('OjsWorkflowBundle:Manager:article.html.twig', array(
                    'articleStep' => $articleStep, 'article' => $article, 'id' => $id));
    }

    /**
     * list articles with given step objectid
     * @param string $id
     */
    public function articlesAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->find($id);

        $articlesStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->findBy(array('step' => $step));
        return $this->render('OjsWorkflowBundle:Manager:articles.html.twig', array(
                    'articlesStep' => $articlesStep, 'step'=> $step,'id' => $id));
    }

}
