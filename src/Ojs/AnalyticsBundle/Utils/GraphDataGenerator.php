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
     * @var string
     */
    private $locale;

    /**
     * GraphDataGenerator constructor.
     * @param $manager
     * @param $locale
     */
    public function __construct($manager, $locale)
    {
        $this->manager  = $manager;
        $this->locale   = $locale;
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
     * @param array $dates
     * @return array
     */
    public function generateJournalViewsData($dates = null)
    {
        $whereDate = '';
        if($dates){
            $today = $dates[0];
            $lastMonthToday = end($dates);
            $whereDate = "AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' ";
        }
        $sql = "SELECT journal_translations.title, SUM(statistic.view) as sum_view FROM statistic "
                ."join journal on statistic.journal_id = journal.id "
                ."join journal_translations on journal.id = journal_translations.translatable_id "
	                ."and journal_translations.locale = '".$this->locale."' "
                ."WHERE journal_id IS NOT NULL "
                .$whereDate
                ."group by journal_id,journal_translations.title "
                ."ORDER BY sum_view DESC "
                ."LIMIT 20; ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('sum_view', 'view');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        return $results;
    }

    /**
     * Returns an array of article download statistics which can be displayed in a table
     *
     * @param array $dates
     * @return array
     */
    public function generateArticleViewsData($dates = null)
    {
        $whereDate = '';
        if($dates){
            $today = $dates[0];
            $lastMonthToday = end($dates);
            $whereDate = "AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' ";
        }
        $sql = "SELECT article_translations.title, SUM(statistic.view) as sum_view FROM statistic "
            ."join article on statistic.article_id = article.id "
            ."join article_translations on article.id = article_translations.translatable_id "
            ."and article_translations.locale = '".$this->locale."' "
            ."WHERE article_id IS NOT NULL "
            .$whereDate
            ."group by article_id,article_translations.title "
            ."ORDER BY sum_view DESC "
            ."LIMIT 20; ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('sum_view', 'view');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        return $results;
    }

    /**
     * Returns an array of issue download statistics which can be displayed in a table
     *
     * @param array $issues
     * @param array $dates
     * @return array
     */
    public function generateIssueFileDownloadsData($dates = null)
    {
        $whereDate = '';
        if($dates){
            $today = $dates[0];
            $lastMonthToday = end($dates);
            $whereDate = "AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' ";
        }
        $sql = "SELECT issue_file_translations.title, SUM(statistic.download) as sum_download FROM statistic "
            ."join issue_file on statistic.issue_file_id = issue_file.id "
            ."join issue_file_translations on issue_file.id = issue_file_translations.translatable_id "
            ."and issue_file_translations.locale = '".$this->locale."' "
            ."WHERE issue_file_id IS NOT NULL "
            .$whereDate
            ."group by issue_file_id,issue_file_translations.title "
            ."ORDER BY sum_download DESC "
            ."LIMIT 20; ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('sum_download', 'download');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        return $results;
    }

    /**
     * Returns an array of article download statistics which can be displayed in a table
     *
     * @param array $dates
     * @return array
     */
    public function generateArticleFileDownloadsData($dates = null)
    {
        $whereDate = '';
        if($dates){
            $today = $dates[0];
            $lastMonthToday = end($dates);
            $whereDate = "AND statistic.date BETWEEN '".$lastMonthToday."' AND '".$today."' ";
        }
        $sql = "SELECT article_file.title, SUM(statistic.download) as sum_download FROM statistic "
                ."join article_file on statistic.article_file_id = article_file.id "
                ."WHERE article_file_id IS NOT NULL "
                .$whereDate
                ."group by article_file_id ,article_file.title "
                ."ORDER BY sum_download DESC "
                ."LIMIT 20 ";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('sum_download', 'download');
        $query = $this->manager->createNativeQuery($sql, $rsm);
        $results = $query->getResult();

        return $results;
    }

    /**
     * Returns an array which can be passed to C3.js for bar chart graph creation
     *
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleBarChartDataDoctrine($articles, $dates)
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
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleFilePieChartDataDoctrine($articles, $dates)
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
    public function generateArticleViewsDataDoctrine($articles, $dates = null)
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
     * Returns an array of article download statistics which can be displayed in a table
     *
     * @param array $articles
     * @param array $dates
     * @return array
     */
    public function generateArticleFileDownloadsDataDoctrine($articles, $dates = null)
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