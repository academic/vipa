<?php

namespace Ojs\ReportBundle\Controller;

 use Ojs\Common\Controller\OjsController as Controller;

class AnalyticsReportController extends Controller
{

    public function indexAction()
    {
        $data = [];

        $journal_service = $this->get('ojs.journal_service');

        $journal = $journal_service->getSelectedJournal();

        $data['journal_stats']  = $journal_service->journalStats($journal);
        $data['article_stats'] = $journal_service->journalsArticlesStats($journal);

        return $this->render('OjsReportBundle:analytics:index.html.twig', $data);
    }
}
