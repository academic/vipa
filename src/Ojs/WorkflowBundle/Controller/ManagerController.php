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

        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->find($id);

        $articleStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->findOneBy(array('step' => $step));
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleStep->getArticleId()); 
        return $this->render('OjsWorkflowBundle:Manager:article.html.twig', array(
                    'articleStep' => $articleStep, 'article' => $article, 'id' => $id));
    }

}
