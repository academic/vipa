<?php

namespace Ojs\ReportBundle\Controller;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Columns;
use APY\DataGridBundle\Grid\Source\Vector;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Services\JournalService;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AnalyticsReportController extends Controller
{

    public function indexAction()
    {
        $data = [];
        /** @var JournalService $journal_service */
        $journal_service = $this->get('ojs.journal_service');
        $journal = $journal_service->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'report')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $article_stats_data = $journal_service->journalsArticlesStats($journal);

        $article_stats_grid = $this->get('grid');
        $article_stats_grid->setId('article_stats_grid');
        $article_stats_source = new Vector($article_stats_data);
        $article_stats_grid->setSource($article_stats_source);
        $article_stats_grid->setDefaultOrder('hit','desc');
        $article_stats_grid->setLimits(7);

        $actionColumn = new ActionsColumn('actions','actions');
        $rowAction = new RowAction('detail','ojs_report_article_report_detail');
        $actionColumn->setRowActions([$rowAction]);
        $article_stats_grid->addColumn($actionColumn);

        $article_stats_grid->isReadyForRedirect();
        $data['article_stats_grid']=$article_stats_grid;


        $article_download_stats_data = $journal_service->getArticlesDownloadStats($journal);

        $article_download_stats_grid = $this->get('grid');
        $article_download_stats_grid->setId('article_download_stats_grid');
        $article_download_stats_source = new Vector($article_download_stats_data);
        $article_download_stats_grid->setSource($article_download_stats_source);
        $article_download_stats_grid->setDefaultOrder('download','desc');
        $article_download_stats_grid->setLimits(7);
        $article_download_stats_grid->isReadyForRedirect();
        $data['article_download_stats_grid']=$article_download_stats_grid;


        $data['journal_stats'] = $journal_service->journalStats($journal);
        return $this->render('OjsReportBundle:analytics:index.html.twig',$data);
    }

    public function detailAction($id)
    {
        $data = [];
        /** @var JournalService $journal_service */
        $journal_service = $this->get('ojs.journal_service');
        $journal = $journal_service->getSelectedJournal();
        $article_stats = $journal_service->getArticleStats($id,$journal);
        $data['article_stats'] = $article_stats;
        return $this->render("OjsReportBundle:article:detail.html.twig",$data);
    }
}
