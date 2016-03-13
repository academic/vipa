<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\AnalyticsBundle\Utils\GraphDataGenerator;
use Ojs\CoreBundle\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;

class StatsController extends OjsController
{
    const DATE_FORMAT = "Y-m-d";

    public function indexAction(Request $request)
    {
        return $this->render('OjsJournalBundle:Stats:stats.html.twig', $this->createStats($request));
    }

    /**
     *  Arranges statistics
     * @return array
     */
    private function createStats(Request $request)
    {
        $generator = new GraphDataGenerator($this->getDoctrine()->getManager(), $request->getLocale());
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $lastMonth = ['x'];
        for ($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($this::DATE_FORMAT, strtotime('-' . $i . ' days'));
        }

        $slicedLastMonth = array_slice($lastMonth, 1);

        $articles = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->findAll();

        $issues = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Issue')
            ->findAll();

        $json = [
            'dates' => $lastMonth,
            'articleViews' => $generator->generateArticleBarChartData($slicedLastMonth),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($slicedLastMonth),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($slicedLastMonth),
        ];

        $data = [
            'stats' => json_encode($json),
            'articles' => $generator->generateArticleViewsData($slicedLastMonth),
            'issueFiles' => $generator->generateIssueFileDownloadsData($slicedLastMonth),
            'articleFiles' => $generator->generateArticleFileDownloadsData($slicedLastMonth),
            'articlesMonthly' => $generator->generateArticleViewsData($slicedLastMonth),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($slicedLastMonth),
        ];

        return $data;
    }
}
