<?php

namespace Ojs\WorkflowBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

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
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->find($articleStep->getStepId());
        list($daysRemaining, $daysOverDue) = \Ojs\Common\Helper\DateHelper::calculateDaysDiff($articleStep->getStartedDate(), $articleStep->getReviewDeadline(), true);
        return $this->render('OjsWorkflowBundle:Manager:article.html.twig', array(
                    'articleStep' => $articleStep,
                    'article' => $article,
                    'id' => $id,
                    'step' => $step,
                    'daysRemaining' => $daysRemaining,
                    'daysOverDue' => $daysOverDue
        ));
    }

    /**
     * 
     * @param string $id 
     */
    public function startReviewAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $articleStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->find($id);
        if ($articleStep) {
            $articleStep->setOwnerUser($this->getUser());
            $dm->persist($articleStep);
            $dm->flush();
        }

        $referer = $request->headers->get('referer');
        return $this->redirect(empty($referer) ? "/" : $referer);
    }

    /**
     * list articles with given step objectid
     * @param string $id
     */
    public function articlesAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->get('doctrine.orm.entity_manager');

        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->find($id);

        $articlesStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")
                ->findBy(
                array('stepId' => $step->getId()), array('finishedDate' => null)
        );
        $ids = [];
        foreach ($articlesStep as $stepNode) {
            $ids[] = $stepNode->getArticleId();
        }

        $query = $em->createQuery('SELECT a FROM OjsJournalBundle:Article a WHERE a.id IN (?1)')
                ->setParameter(1, $ids);
        $articles = $query->getResult();
        return $this->render('OjsWorkflowBundle:Manager:articles.html.twig', array(
                    'articles' => $articles, 'articlesStep' => $articlesStep, 'step' => $step, 'id' => $id));
    }

}
