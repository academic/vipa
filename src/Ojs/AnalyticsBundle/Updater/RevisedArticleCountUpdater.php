<?php
/**
 * www
 */
namespace Ojs\AnalyticsBundle\Updater;

use Ojs\WorkflowBundle\Document\ArticlereviewStepRepository;

class RevisedArticleCountUpdater extends  Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        /** @var ArticlereviewStepRepository $arsr */
        $arsr = $this->dm->getRepository('OjsWorkflowBundle:ArticleReviewStep');

        $all = $arsr->findBy(['']);

    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\WorkflowBundle\Document\ArticleReviewStep';
    }
}
