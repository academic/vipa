<?php

namespace Ojs\WorkflowBundle\Controller;

use MongoDBODMProxies\__CG__\Ojs\WorkflowBundle\Document\ArticleReviewStep;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for journal's all users
 * actions will check roles in their logic
 */
class ManagerController extends \Ojs\Common\Controller\OjsController {

    /**
     * Preview article's current data with given object id
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->get('doctrine')->getManager();

        $articleStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->find($id);
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleStep->getArticleId());
        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->find($articleStep->getStep()->getId());
        list($daysRemaining, $daysOverDue) = \Ojs\Common\Helper\DateHelper::calculateDaysDiff(
                        $articleStep->getStartedDate(), $articleStep->getReviewDeadline(), true
        );   
        return $this->render('OjsWorkflowBundle:Manager:article.html.twig', array(
                    'articleStep' => $articleStep,
                    'article' => $article,
                    'id' => $id,
                    'step' => $step,
                    'prevStep' => $articleStep->getFrom(),
                    'daysRemaining' => $daysRemaining,
                    'daysOverDue' => $daysOverDue
        ));
    }

    /**
     * list articles with given step objectid
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->get('doctrine.orm.entity_manager');

        $step = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                ->find($id);

        $articlesStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")
                ->findBy(
                array('step.$id' => new \MongoId($step->getId())), array('finishedDate' => null)
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

    /**
     * starting review means : fill "owneruser" attribute with current user data
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function startReviewAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $articleStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->find($id);
        $this->throw404IfNotFound($articleStep);
        $articleStep->setOwnerUser($this->getUser());
        $dm->persist($articleStep);
        $dm->flush();
        return $this->redirect($this->generateUrl('article_step_preview', array('id' => $id)));
    }

    /**
     * next means: update article's reviewed data and duplicate article_workflow_step document
     * @param Request $request
     * @param $id string
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function nextAction(Request $request, $id) {
        $nextStepId = $request->get('nextStepId');

        $dm = $this->get('doctrine_mongodb')->getManager();

        $articleStep = $dm->getRepository("OjsWorkflowBundle:ArticleReviewStep")->find($id);

        if (!$articleStep) {
            throw $this->createNotFoundException($this->get('translator')->trans('Article review step not found'));
        }
        /* @var $nextStep JournalWorkflowStep */
        $nextStep = $dm->getRepository("OjsWorkflowBundle:JournalWorkflowStep")->find($nextStepId);
        if (!$nextStep) {
            throw $this->createNotFoundException($this->get('translator')->trans('Selected next step not found'));
        }

        $newStep = clone $articleStep;
        $newStep->setStep($nextStep);
        $newStep->setStatusText($nextStep->getStatus());
        $deadline = new \DateTime();
        $deadline->modify("+" . $nextStep->getMaxdays() . " day");
        $newStep->setReviewDeadline($deadline);
        $newStep->setOwnerUser(false);
        $newStep->setFrom($articleStep);
        $newStep->setAction($request->get('reviewResultCode'));
        $newStep->setNote(null);
        $newStep->setReviewNotes($request->get('notes'));
        //$newStep

        $dm->persist($newStep);
        $dm->flush();

        $articleStep->setTo($newStep);
        $articleStep->setFinishedDate(new \DateTime());
        /* generate reviewform and append to reviewNotes */
        $reviewFormResults = '';
        $reviewForm = $dm->getRepository("OjsWorkflowBundle:ReviewForm")->find($request->get('reviewFormId'));
        $reviewFormItems = $dm->getRepository("OjsWorkflowBundle:ReviewForm")->getItems($reviewForm->getId());

        /* @var  $item      \Ojs\WorkflowBundle\Document\ReviewFormItem */
        foreach ($reviewFormItems as $item) {
            $reviewFormResults.='<div class="reviewFormItemRow">';
            $reviewFormResults.='<strong class="reviewFormItemLabel">' . $item->getTitle() . '</strong> ';
            if ($item->getInputType() == 'checkboxes') {
                foreach ($request->get($item->getId()) as $value) {
                    $reviewFormResults.='<span class="reviewFormItemValue">' . $value . '</span> ';
                }
            } else {
                $reviewFormResults.='<span class="reviewFormItemValue">' . $request->get($item->getId()) . '</span>';
            }
            $reviewFormResults.='<br></div>';
        }
        $articleStep->setReviewFormResults($reviewFormResults);
        $articleStep->setReviewNotes($request->get('notes'));
        $dm->persist($articleStep);
        $dm->flush();
        return $this->redirect($this->generateUrl('ojs_user_index'));
    }

}
