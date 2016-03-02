<?php

namespace Ojs\AnalyticsBundle\Utils;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Ojs\AnalyticsBundle\Entity\ArticleFileStatistic;
use Ojs\AnalyticsBundle\Entity\ArticleStatistic;
use Ojs\AnalyticsBundle\Entity\IssueFileStatistic;
use Ojs\AnalyticsBundle\Entity\JournalStatistic;
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
     * Returns generator's date format
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this::DATE_FORMAT;
    }

    /**
     * Returns an array which can be passed to C3.js for bar chart graph creation
     *
     * @param array $dates
     * @return array
     */
    public function generateJournalBarChartData($dates)
    {
        $today = $dates[0];
        $lastMonthToday = end($dates);
        $sql = "SELECT statistic.date as date, SUM(statistic.view) AS view FROM statistic "
                ."where statistic.journal_id IS NOT NULL "
	                ."AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' "
                ."GROUP BY statistic.date "
                ."ORDER BY statistic.date ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('view', 'view');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        $journalViews = ['View'];

        foreach ($dates as $date) {
            $persisted = false;
            foreach($results as $result){
                if($result['date'] == $date){
                    $journalViews[] = (int)$result['view'];
                    $persisted = true;
                }
            }
            if(!$persisted){
                $journalViews[] = 0;
            }
        }
        return $journalViews;
    }
    
    /**
     * Returns an array which can be passed to C3.js for bar chart graph creation
     *
     * @param array $dates
     * @return array
     */
    public function generateArticleBarChartData($dates)
    {
        $today = $dates[0];
        $lastMonthToday = end($dates);
        $sql = "SELECT statistic.date as date, SUM(statistic.view) AS view FROM statistic "
            ."where statistic.article_id IS NOT NULL "
            ."AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' "
            ."GROUP BY statistic.date "
            ."ORDER BY statistic.date ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('view', 'view');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        $articleViews = ['View'];

        foreach ($dates as $date) {
            $persisted = false;
            foreach($results as $result){
                if($result['date'] == $date){
                    $articleViews[] = (int)$result['view'];
                    $persisted = true;
                }
            }
            if(!$persisted){
                $articleViews[] = 0;
            }
        }
        return $articleViews;
    }

    /**
     * Returns an array which can be passed to C3.js for pie chart graph creation
     *
     * @param array $dates
     * @return array
     */
    public function generateIssueFilePieChartData($dates)
    {
        $today = $dates[0];
        $lastMonthToday = end($dates);
        $sql = "SELECT statistic.date as date, SUM(statistic.download) AS download FROM statistic "
            ."where statistic.issue_file_id IS NOT NULL "
            ."AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' "
            ."GROUP BY statistic.date "
            ."ORDER BY statistic.date ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('download', 'download');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        $issueFileDownloads = ['Download'];

        foreach ($dates as $date) {
            $persisted = false;
            foreach($results as $result){
                if($result['date'] == $date){
                    $issueFileDownloads[] = (int)$result['download'];
                    $persisted = true;
                }
            }
            if(!$persisted){
                $issueFileDownloads[] = 0;
            }
        }
        return $issueFileDownloads;
    }
    /**
     * Returns an array which can be passed to C3.js for pie chart graph creation
     *
     * @param array $dates
     * @return array
     */
    public function generateArticleFilePieChartData($dates)
    {
        $today = $dates[0];
        $lastMonthToday = end($dates);
        $sql = "SELECT statistic.date as date, SUM(statistic.download) AS download FROM statistic "
            ."where statistic.article_file_id IS NOT NULL "
            ."AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' "
            ."GROUP BY statistic.date "
            ."ORDER BY statistic.date ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('download', 'download');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        $articleFileDownloads = ['Download'];

        foreach ($dates as $date) {
            $persisted = false;
            foreach($results as $result){
                if($result['date'] == $date){
                    $articleFileDownloads[] = (int)$result['download'];
                    $persisted = true;
                }
            }
            if(!$persisted){
                $articleFileDownloads[] = 0;
            }
        }
        return $articleFileDownloads;
    }

    /**
     * Returns an array of journal download statistics which can be displayed in a table
     *
     * @param array $journals
     * @param array $dates
     * @return array
     */
    public function generateJournalViewsData($journals, $dates = null)
    {
        $journalStatRepo = $this->manager->getRepository('OjsAnalyticsBundle:JournalStatistic');
        $stats = $journalStatRepo->getMostViewed($journals, $dates, 10);
        $result = [];

        foreach ($stats as $stat) {
            /** @var JournalStatistic $journalStat */
            $journalStat = $stat[0];
            $result[] = array(
                $journalStat->getJournal()->getTitle(),
                $stat[1]
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