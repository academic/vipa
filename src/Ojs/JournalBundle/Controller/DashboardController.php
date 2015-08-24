<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\AnalyticsBundle\Entity\ArticleFileStatistic;
use Ojs\AnalyticsBundle\Entity\ArticleStatistic;
use Ojs\AnalyticsBundle\Entity\IssueFileStatistic;
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

        $articleStats = $articleStatRepo->findByArticles($articles, array_slice($lastMonth, 1));

        $articleViews = ['View'];
        foreach (array_slice($lastMonth, 1) as $date) {
            /** @var ArticleStatistic $stat */
            $total = 0;
            $stat = $articleStats->first();
            while ($stat && $stat->getDate()->format($this::DATE_FORMAT) == $date) {
                $total += $stat->getView();
                $articleStats->removeElement($stat);
                $stat = $articleStats->first();
            }

            $articleViews[] = $total;
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
                        $articleFileDownloads['charts'][$key][] = [$articleFile->getTitle(), $totalDownloads, 'articleFile'.$articleFile->getId()];
                    }
                }
            }
        }

        $issues = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Issue')
            ->findBy(['journal' => $journal]);

        $issueFileStatRepo = $this
            ->getDoctrine()
            ->getRepository('OjsAnalyticsBundle:IssueFileStatistic');

        $issueFileDownloads = [];
        $issueFileDownloads['mainChart'] = [];
        $issueFileDownloads['mainChartNames'] = [];
        $issueFileDownloads['charts'] = [];
        foreach ($issues as $issue)
        {
            $key = $issue->getId();
            $allFilesStat = $issueFileStatRepo->getTotalDownloadsOfAllFiles($issue, array_slice($lastMonth, 1));

            if (!empty($allFilesStat)) {
                $totalDownloadsOfAllFiles = $allFilesStat[0][1];
                $issueFileDownloads['mainChart'][] = [$key, $totalDownloadsOfAllFiles];
                $issueFileDownloads['mainChartNames'][] = [$key, $issue->getTitle()];

                foreach ($issue->getIssueFiles() as $issueFile) {
                    $fileStat = $issueFileStatRepo->getTotalDownloads($issueFile, array_slice($lastMonth, 1));

                    if (!empty($fileStat)) {
                        $totalDownloads = $fileStat[0][1];
                        $issueFileDownloads['charts'][$key][] = [$issueFile->getTitle(), $totalDownloads, 'issueFile'.$issueFile->getId()];
                    }
                }
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
        $issueFilesMonthlyStats = $issueFileStatRepo->getMostDownloadedFiles($issues, array_slice($lastMonth, 1), 10);
        foreach ($issueFilesMonthlyStats as $stat) {
            /** @var IssueFileStatistic $issueFileStat */
            $issueFileStat = $stat[0];
            $totalDownloads = $stat[1];
            $issueFilesMonthly[] = array(
                $issueFileStat->getIssueFile()->getTitle(),
                $totalDownloads
            );
        }

        $issueFilesAllTime = [];
        $issueFilesAllTimeStats = $issueFileStatRepo->getMostDownloadedFiles($issues, null, 10);
        foreach ($issueFilesAllTimeStats as $stat) {
            /** @var IssueFileStatistic $issueFileStat */
            $issueFileStat = $stat[0];
            $totalDownloads = $stat[1];
            $issueFilesAllTime[] = array(
                $issueFileStat->getIssueFile()->getTitle(),
                $totalDownloads
            );
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