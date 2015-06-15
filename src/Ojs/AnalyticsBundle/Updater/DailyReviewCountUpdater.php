<?php

namespace Ojs\AnalyticsBundle\Updater;

use Ojs\WorkflowBundle\Document\ArticleReviewStep;
use Ojs\WorkflowBundle\Document\ArticlereviewStepRepository;

class DailyReviewCountUpdater extends Updater implements UpdaterInterface
{
    public function update()
    {
        // TODO: Implement update() method.
    }

    public function count()
    {
        /** @var ArticlereviewStepRepository $arsr */
        $arsr = $this->dm->getRepository('OjsWorkflowBundle:ArticleReviewStep');

        $all = $arsr->findBy(['rootNode' => true]);

        $revises = [];
        foreach ($all as $r) {
            /** @var ArticleReviewStep $r */
            $journal = $this->em->find('OjsJournalBundle:Article', $r->getArticleId())->getJournal();
            if (isset($revises[$journal->getId()])
                && in_array($r->getArticleId(), $revises[$journal->getId()])
            ) {
                continue;
            }
            $revises[$journal->getId()][] = $r->getArticleId();
        }

        return $revises;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Ojs\JournalBundle\Entity\Journal';
    }
}
