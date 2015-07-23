<?php

namespace Ojs\ReportBundle\Controller;

use Elastica\Aggregation\DateHistogram;
use Elastica\Aggregation\Filter;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\ValueCount;
use Elastica\Filter\Bool;
use Elastica\Filter\Term;
use Elastica\Query;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserReportController extends Controller
{

    public function indexAction()
    {
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'report')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $results = $this->get('fos_elastica.index.search.user')->search($this->getStatsQuery($journal));
        $counter = $this->get('fos_elastica.index.search.user')->count($this->getCountQuery($journal));
        $datedResult = $this->get('fos_elastica.index.search.user')->search($this->getDatedStatsQuery($journal));
        $data = [];
        $data['aggs'] = $results->getAggregations();
        $data['datedAggs'] = $datedResult->getAggregations();
        $data['total'] = $counter;
        return $this->render('OjsReportBundle:user:index.html.twig',$data);
    }


    private function getStatsQuery(Journal $journal)
    {
        $filter = new Term();
        $filter->setTerm('journalUsers.journal.id',$journal->getId());
        $filterQuery = new Query\Filtered();
        $filterQuery->setFilter($filter);
        $query = new Query($filterQuery);

        $titleAggregation = new Terms('title');
        $titleAggregation->setField('title');

        $query->addAggregation($titleAggregation);

        $genderAggregation = new Terms('gender');
        $genderAggregation->setField('gender');

        $query->addAggregation($genderAggregation);

        $query->setSize(0);
        return $query;
    }

    private function getDatedStatsQuery($journal){
        $filter = new Term();
        $filter->setTerm('journalUsers.journal.id',$journal->getId());
        $filterQuery = new Query\Filtered();
        $filterQuery->setFilter($filter);
        $query = new Query($filterQuery);

        $dateHistogram = new DateHistogram('dateHistogram','created','month');
        $dateHistogram->setFormat("YYYY-MM-dd");
        $query->addAggregation($dateHistogram);
        $query->setSize(0);

        return $query;
    }

    private function getCountQuery($journal)
    {
        $filter = new Term();
        $filter->setTerm('journalUsers.journal.id',$journal->getId());
        $filterQuery = new Query\Filtered();
        $filterQuery->setFilter($filter);
        $query = new Query($filterQuery);
        $query->setSize(0);
        return $query;
    }
}
