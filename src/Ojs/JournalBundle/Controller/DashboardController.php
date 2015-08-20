<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\AnalyticsBundle\Entity\ArticleFileStatistic;
use Ojs\AnalyticsBundle\Entity\ArticleStatistic;
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
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($this::DATE_FORMAT, strtotime('-' . $i . ' days'));
        }

        $articles = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->findBy(['journal' => $journal]);

        $articleStatRepo = $this
            ->getDoctrine()
            ->getRepository('OjsAnalyticsBundle:ArticleStatistic');

        $articleStats = $articleStatRepo->getByArticlesAndDates($articles, array_slice($lastMonth, 1));

        $articleViews = ['View'];
        foreach (array_slice($lastMonth, 1) as $date) {
            /** @var ArticleStatistic $stat */
            $stat = $articleStats->first();
            if ($stat && $date == $stat->getDate()->format($this::DATE_FORMAT)) {
                $articleViews[] = $stat->getView();
                $articleStats->removeElement($stat);
            } else {
                $articleViews[] = 0;
            }
        }

        $articleFileStatRepo = $this
            ->getDoctrine()
            ->getRepository('OjsAnalyticsBundle:ArticleFileStatistic');

        $articleFileDownloads = [];
        $articleFileDownloads['mainChart'] = [];
        $articleFileDownloads['mainChartNames'] = [];
        $articleFileDownloads['charts'] = [];

        foreach ($articles as $article) {
            $key = $article->getId();
            $allFilesStat = $articleFileStatRepo->getTotalDownloadsOfAllFiles($article, array_slice($lastMonth, 1));

            if (!empty($allFilesStat)) {
                $totalDownloadsOfAllFiles = $allFilesStat[0][1];
                $articleFileDownloads['mainChart'][] = [$key, $totalDownloadsOfAllFiles];
                $articleFileDownloads['mainChartNames'][] = [$key, $article->getTitle()];

                foreach ($article->getArticleFiles() as $articleFile) {
                    $fileStat = $articleFileStatRepo->getTotalDownloads($articleFile, array_slice($lastMonth, 1));

                    if (!empty($fileStat)) {
                        $totalDownloads = $fileStat[0][1];
                        $articleFileDownloads['charts'][$key][] = [$articleFile->getTitle(), $totalDownloads];
                    }
                }
            }
        }

        $issueFileDownloads = [];
        $issueFileDownloads['mainChart'] = [];
        $issueFileDownloads['mainChartNames'] = [];
        $issueFileDownloads['charts'] = [];

        for($i = 0; $i < rand(2, 20); $i++)
        {
            $key = 'aUniqueIdHere'.rand(0, 10000);
            array_push(
                $issueFileDownloads['mainChart'],
                [$key, rand(0, 100)]
            );
            array_push(
                $issueFileDownloads['mainChartNames'],
                [$key, 'Makale '.$i]
            );
        }

        foreach($issueFileDownloads['mainChart'] as $articleFile)
        {
            $issueFileDownloads['charts'][$articleFile[0]] = [];
            for($i = 0; $i < rand(2, 5); $i++)
            {
                array_push(
                    $issueFileDownloads['charts'][$articleFile[0]],
                    ['Veri '.$i, rand(0, 100)]
                );
            }
        }

        $articlesMonthly = [];
        $articlesMonthlyStats = $articleStatRepo->getMostViewed($articles, array_slice($lastMonth, 1), 10);
        foreach ($articlesMonthlyStats as $stat) {
            /** @var ArticleStatistic $articleStat */
            $articleStat = $stat[0];
            $articlesMonthly[] = array(
                $articleStat->getArticle()->getTitle(),
                $stat['totalViews']
            );
        }

        $articlesAllTime = [];
        $articlesAllTimeStats = $articleStatRepo->getMostViewed($articles, null, 10);
        foreach ($articlesAllTimeStats as $stat) {
            /** @var ArticleStatistic $articleStat */
            $articleStat = $stat[0];
            $articlesAllTime[] = array(
                $articleStat->getArticle()->getTitle(),
                $stat['totalViews']
            );
        }

        $articleFilesMonthly = [];
        $articleFilesMonthlyStats = $articleFileStatRepo->getMostDownloadedFiles($articles, array_slice($lastMonth, 1), 10);
        foreach ($articleFilesMonthlyStats as $stat) {
            /** @var ArticleFileStatistic $articleFileStat */
            $articleFileStat = $stat[0];
            $totalDownloads = $stat[1];
            $articleFilesMonthly[] = array(
                $articleFileStat->getArticleFile()->getTitle(),
                $totalDownloads
            );
        }

        $articleFilesAllTime = [];
        $articleFilesAllTimeStats = $articleFileStatRepo->getMostDownloadedFiles($articles, null, 10);
        foreach ($articleFilesAllTimeStats as $stat) {
            /** @var ArticleFileStatistic $articleFileStat */
            $articleFileStat = $stat[0];
            $totalDownloads = $stat[1];
            $articleFilesAllTime[] = array(
                $articleFileStat->getArticleFile()->getTitle(),
                $totalDownloads
            );
        }

        $issueFilesMonthly = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($issueFilesMonthly, [
                'Issue File '.rand(0, 100), rand((90 - $i*10), (100 - $i*10))
            ]);
        }

        $issueFilesAllTime = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($issueFilesAllTime, [
                'Issue File '.rand(0, 100), rand((900 - $i*100), (1000 - $i*100))
            ]);
        }


        $json = [
            'dates' => $lastMonth,
            'articleViews' => $articleViews,
            'articleFileDownloads' => $articleFileDownloads,
            'issueFileDownloads' => $issueFileDownloads
        ];

        $data = [
            'stats' => json_encode($json),
            'articlesMonthly' => $articlesMonthly,
            'articles' => $articlesAllTime,
            'articleFilesMonthly' => $articleFilesMonthly,
            'articleFiles' => $articleFilesAllTime,
            'issueFilesMonthly' => $issueFilesMonthly,
            'issueFiles' => $issueFilesAllTime
        ];

        return $data;
    }
}