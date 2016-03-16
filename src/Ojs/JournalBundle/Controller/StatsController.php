<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController;
use Ojs\JournalBundle\Entity\Journal;

class StatsController extends OjsController
{
    const DATE_FORMAT = "Y-m-d";

    public function indexAction()
    {
        $journalService = $this->get('ojs.journal_service');
        $journal = $journalService->getSelectedJournal();
        $cache = $this->get('file_cache');
        if(!$cache->contains('journal_'.$journal->getId().'_statistics')){
            $this->cacheJournalStats($journal);
        }
        return $this->render('OjsJournalBundle:Stats:stats.html.twig', $cache->fetch('journal_'.$journal->getId().'_statistics'));
    }

    /**
     * @param Journal $journal
     * @return true
     */
    private function cacheJournalStats(Journal $journal)
    {
        $cache = $this->container->get('file_cache');
        $generator = $this->container->get('ojs.graph.data.generator');

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($generator->getDateFormat(), strtotime('-' . $i . ' days'));
        }
        $slicedLastMonth = array_slice($lastMonth, 1);

        $json = [
            'dates' => $lastMonth,
            'journalViews' => $generator->generateJournalBarChartData($slicedLastMonth, $journal),
            'articleViews' => $generator->generateArticleBarChartData($slicedLastMonth, $journal),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($slicedLastMonth, $journal),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($slicedLastMonth, $journal),
        ];

        $data = [
            'stats' => json_encode($json),
            'articles' => $generator->generateArticleViewsData(null , $journal),
            'issueFiles' => $generator->generateIssueFileDownloadsData(null, $journal),
            'articleFiles' => $generator->generateArticleFileDownloadsData(null, $journal),
            'articlesMonthly' => $generator->generateArticleViewsData($slicedLastMonth, $journal),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($slicedLastMonth, $journal),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($slicedLastMonth, $journal),
        ];

        $cache->save('journal_'.$journal->getId().'_statistics', $data, 1800);

        return true;
    }
}
