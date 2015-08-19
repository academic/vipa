<?php

namespace Ojs\JournalBundle\Controller;

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

        $articleFileDownloads = ['Download'];
        for($i = 0; $i < 30; $i++)
        {
            array_push($articleFileDownloads, rand(0, 100));
        }

        $issueFileDownloads = ['Download'];
        for($i = 0; $i < 30; $i++)
        {
            array_push($issueFileDownloads, rand(0, 100));
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
        for($i = 0; $i < 10; $i++)
        {
            array_push($articleFilesMonthly, [
                'Article File '.rand(0, 100), rand((90 - $i*10), (100 - $i*10))
            ]);
        }

        $articleFilesAllTime = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($articleFilesAllTime, [
                'Article File '.rand(0, 100), rand((900 - $i*100), (1000 - $i*100))
            ]);
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