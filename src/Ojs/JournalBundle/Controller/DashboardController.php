<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\AnalyticsBundle\Utils\GraphDataGenerator;
use Ojs\Common\Controller\OjsController;

class DashboardController extends OjsController
{
    const DATE_FORMAT = "Y-m-d";

    public function indexAction()
    {
        return $this->render('OjsJournalBundle:Dashboard:dashboard.html.twig', $this->createStats());
    }

    /**
     *  Arranges statistics
     *  @return array
     */
    private function createStats()
    {
        $generator = new GraphDataGenerator($this->getDoctrine()->getManager());
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($this::DATE_FORMAT, strtotime('-' . $i . ' days'));
        }

        $slicedLastMonth = array_slice($lastMonth, 1);

        $articles = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->findBy(['journal' => $journal]);

        $issues = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Issue')
            ->findBy(['journal' => $journal]);

        $json = [
            'dates' => $lastMonth,
            'articleViews' => $generator->generateArticleBarChartData($articles, $slicedLastMonth),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($issues, $slicedLastMonth),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($articles, $slicedLastMonth),
        ];

        $data = [
            'stats' => json_encode($json),
            'articles' => $generator->generateArticleViewsData($articles),
            'issueFiles' => $generator->generateIssueFileDownloadsData($issues),
            'articleFiles' => $generator->generateArticleFileDownloadsData($articles),
            'articlesMonthly' => $generator->generateArticleViewsData($articles, $slicedLastMonth),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($issues, $slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($articles, $slicedLastMonth),
        ];
        
        return $data;
    }
}