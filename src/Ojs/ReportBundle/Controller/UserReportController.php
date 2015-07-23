<?php

namespace Ojs\ReportBundle\Controller;

use Elastica\Aggregation\DateHistogram;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\ValueCount;
use Elastica\Query;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserReportController extends Controller
{

    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'report')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }

        $results = $this->get('fos_elastica.index.search.user')->search($this->getStatsQuery());
        $datedResult = $this->get('fos_elastica.index.search.user')->search($this->getDatedStatsQuery());
        $data = [];
        $data['aggs'] = $results->getAggregations();
        $data['datedAggs'] = $datedResult->getAggregations();
        return $this->render('OjsReportBundle:user:index.html.twig',$data);
    }


    private function getStatsQuery()
    {
        $query = new Query(new Query\MatchAll());
        $titleAggregation = new Terms('title');
        $titleAggregation->setField('title');

        $query->addAggregation($titleAggregation);

        $genderAggregation = new Terms('gender');
        $genderAggregation->setField('gender');

        $query->addAggregation($genderAggregation);

        $query->setSize(0);
        return $query;
    }

    private function getDatedStatsQuery(){
        $query = new Query(new Query\MatchAll());
        $dateHistogram = new DateHistogram('dateHistogram','created','month');
        $dateHistogram->setFormat("YYYY-MM-dd");
        $query->addAggregation($dateHistogram);
        $query->setSize(0);

        return $query;
    }
}
