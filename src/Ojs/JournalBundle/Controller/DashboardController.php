<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Controller\OjsController;

class DashboardController extends OjsController
{
    public function indexAction()
    {
        return $this->render('OjsJournalBundle:Dashboard:dashboard.html.twig', $this->createStats());
    }

    /**
     *  Creates sample stats
     *  @TODO Stats need to be taken from database
     *
     *  @return Array
     */
    private function createStats()
    {
        $dates = ['x'];
        for($i = 1; $i < 10; $i++)
        {
            array_push($dates, '2015-08-0'.$i);
        }
        for($i = 10; $i < 31; $i++)
        {
            array_push($dates, '2015-08-'.$i);
        }

        $articleViews = ['View'];
        for($i = 0; $i < 30; $i++)
        {
            array_push($articleViews, rand(0, 100));
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
        for($i = 0; $i < 10; $i++)
        {
            array_push($articlesMonthly, [
                'Article '.rand(0, 100), rand((90 - $i*10), (100 - $i*10))
            ]);
        }

        $articles = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($articles, [
                'Article '.rand(0, 100), rand((900 - $i*100), (1000 - $i*100))
            ]);
        }

        $articleFilesMonthly = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($articleFilesMonthly, [
                'Article File '.rand(0, 100), rand((90 - $i*10), (100 - $i*10))
            ]);
        }

        $articleFiles = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($articleFiles, [
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

        $issueFiles = [];
        for($i = 0; $i < 10; $i++)
        {
            array_push($issueFiles, [
                'Issue File '.rand(0, 100), rand((900 - $i*100), (1000 - $i*100))
            ]);
        }


        $json = [
            'dates' => $dates,
            'articleViews' => $articleViews,
            'articleFileDownloads' => $articleFileDownloads,
            'issueFileDownloads' => $issueFileDownloads
        ];

        $data = [
            'stats' => json_encode($json),
            'articlesMonthly' => $articlesMonthly,
            'articles' => $articles,
            'articleFilesMonthly' => $articleFilesMonthly,
            'articleFiles' => $articleFiles,
            'issueFilesMonthly' => $issueFilesMonthly,
            'issueFiles' => $issueFiles
        ];

        return $data;
    }
}