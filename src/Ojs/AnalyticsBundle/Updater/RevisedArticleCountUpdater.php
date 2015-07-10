<?php
/**
 * www
 */
namespace Ojs\AnalyticsBundle\Updater;

class RevisedArticleCountUpdater extends Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\WorkflowBundle\Document\ArticleReviewStep';
    }
}
