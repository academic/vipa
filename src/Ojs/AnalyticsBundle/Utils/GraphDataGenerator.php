<?php

namespace Ojs\AnalyticsBundle\Utils;

use Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Entity\ArticleFileStatistic;
use Ojs\AnalyticsBundle\Entity\ArticleStatistic;
use Ojs\AnalyticsBundle\Entity\IssueFileStatistic;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Issue;

class GraphDataGenerator
{
    const DATE_FORMAT = "Y-m-d";

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * GraphDataGenerator constructor.
     * @param $manager
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Returns an array which can be passed to C3.js for bar chart graph creation
     *
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleBarChartData($articles, $dates)
    {
        $articleStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:ArticleStatistic');
        $articleStats = $articleStatRepo->findByArticles($articles, $dates);
        $articleViews = ['View'];

        foreach ($dates as $date) {
            $total = 0;
            /** @var ArticleStatistic $stat */
            $stat = $articleStats->first();

            while ($stat && $stat->getDate()->format($this::DATE_FORMAT) == $date) {
                $total += $stat->getView();
                $articleStats->removeElement($stat);
                $stat = $articleStats->first();
            }

            $articleViews[] = $total;
        }

        return $articleViews;
    }

    /**
     * Returns an array which can be passed to C3.js for pie chart graph creation
     *
     * @param array $issues
     * @param array $dates
     * @return array
     */
    public function generateIssueFilePieChartData($issues, $dates)
    {
        $issueFileStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:IssueFileStatistic');

        $issueFileDownloads = [];
        $issueFileDownloads['mainChart'] = [];
        $issueFileDownloads['mainChartNames'] = [];
        $issueFileDownloads['charts'] = [];

        /** @var Issue $issue */
        foreach ($issues as $issue)
        {
            $key = $issue->getId();
            $allFilesStat = $issueFileStatRepo->getTotalDownloadsOfAllFiles($issue, $dates);

            if (!empty($allFilesStat)) {
                $totalDownloadsOfAllFiles = $allFilesStat[0][1];
                $issueFileDownloads['mainChart'][] = [$key, $totalDownloadsOfAllFiles];
                $issueFileDownloads['mainChartNames'][] = [$key, $issue->getTitle()];

                foreach ($issue->getIssueFiles() as $issueFile) {
                    $fileStat = $issueFileStatRepo->getTotalDownloads($issueFile, $dates);

                    if (!empty($fileStat)) {
                        $totalDownloads = $fileStat[0][1];
                        $issueFileDownloads['charts'][$key][] = [
                            $issueFile->getTitle(),
                            $totalDownloads,
                            'issueFile'.$issueFile->getId()
                        ];
                    }
                }
            }
        }

        return $issueFileDownloads;
    }
    /**
     * Returns an array which can be passed to C3.js for pie chart graph creation
     *
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleFilePieChartData($articles, $dates)
    {
        $articleFileStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:ArticleFileStatistic');

        $articleFileDownloads = [];
        $articleFileDownloads['mainChart'] = [];
        $articleFileDownloads['mainChartNames'] = [];
        $articleFileDownloads['charts'] = [];

        /** @var Article $article */
        foreach ($articles as $article) {
            $key = $article->getId();
            $allFilesStat = $articleFileStatRepo->getTotalDownloadsOfAllFiles($article, $dates);

            if (!empty($allFilesStat)) {
                $totalDownloadsOfAllFiles = $allFilesStat[0][1];
                $articleFileDownloads['mainChart'][] = [$key, $totalDownloadsOfAllFiles];
                $articleFileDownloads['mainChartNames'][] = [$key, $article->getTitle()];

                foreach ($article->getArticleFiles() as $articleFile) {
                    $fileStat = $articleFileStatRepo->getTotalDownloads($articleFile, $dates);

                    if (!empty($fileStat)) {
                        $totalDownloads = $fileStat[0][1];
                        $articleFileDownloads['charts'][$key][] = [
                            $articleFile->getTitle(),
                            $totalDownloads,
                            'articleFile'.$articleFile->getId()];
                    }
                }
            }
        }
        
        return $articleFileDownloads;
    }

    /**
     * Returns an array of article download statistics which can be displayed in a table
     *
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleViewsData($articles, $dates = null)
    {
        $articleStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:ArticleStatistic');
        $stats = $articleStatRepo->getMostViewed($articles, $dates, 10);
        $result = [];

        foreach ($stats as $stat) {
            /** @var ArticleStatistic $articleStat */
            $articleStat = $stat[0];
            $result[] = array(
                $articleStat->getArticle()->getTitle(),
                $stat['totalViews']
            );
        }

        return $result;
    }

    /**
     * Returns an array of issue download statistics which can be displayed in a table
     *
     * @param array $issues
     * @param array $dates
     * @return array
     */
    public function generateIssueFileDownloadsData($issues, $dates = null)
    {
        $issueFileStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:IssueFileStatistic');
        $issueFileStats = $issueFileStatRepo->getMostDownloadedFiles($issues, $dates, 10);
        $result = [];

        foreach ($issueFileStats as $stat) {
            /** @var IssueFileStatistic $issueFileStat */
            $issueFileStat = $stat[0];
            $totalDownloads = $stat[1];
            $result[] = array(
                $issueFileStat->getIssueFile()->getTitle(),
                $totalDownloads
            );
        }

        return $result;
    }

    /**
     * Returns an array of article download statistics which can be displayed in a table
     *
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleFileDownloadsData($articles, $dates = null)
    {
        $articleFileStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:ArticleFileStatistic');
        $articleFileStats = $articleFileStatRepo->getMostDownloadedFiles($articles, $dates, 10);
        $result = [];

        foreach ($articleFileStats as $stat) {
            /** @var ArticleFileStatistic $articleFileStat */
            $articleFileStat = $stat[0];
            $totalDownloads = $stat[1];
            $result[] = array(
                $articleFileStat->getArticleFile()->getTitle(),
                $totalDownloads
            );
        }

        return $result;
    }
}