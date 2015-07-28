<?php

namespace Ojs\ReportBundle\Controller;

use Elastica\Aggregation\DateHistogram;
use Elastica\Aggregation\Terms;
use Elastica\Filter\Term;
use Elastica\Query;
use Elastica\Query\Filtered;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleReportController extends Controller
{

    public function indexAction()
    {
        /*
        $em = $this->getDoctrine()->getManager();
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $data = $dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        */
        $journal = $this->get('ojs.journal_service')->getSelectedJournal();
        if (!$this->isGranted('VIEW', $journal, 'report')) {
            throw new AccessDeniedException("You are not authorized for view this page");
        }
        $results = $this->get('fos_elastica.index.search.articles')->search($this->getStatsQuery($journal));
        $counter = $this->get('fos_elastica.index.search.articles')->count($this->getCountQuery($journal));
        $data = [];
        $data['aggs'] = $results->getAggregations();
        $data['total'] = $counter;

        return $this->render('OjsReportBundle:article:index.html.twig',$data);
    }

    private function getStatsQuery(Journal $journal)
    {
        $filter = new Term();
        $filter->setTerm('journal.id',$journal->getId());
        $filterQuery = new Filtered();
        $filterQuery->setFilter($filter);
        $query = new Query($filterQuery);

        $dateHistogram = new DateHistogram('dateHistogram','created','month');
        $dateHistogram->setFormat("YYYY-MM-dd");
        $query->addAggregation($dateHistogram);

        $genderAggregation = new Terms('language');
        $genderAggregation->setField('locale');
        $query->addAggregation($genderAggregation);

        $subjectAggregation = new Terms('subjects');
        $subjectAggregation->setField('subjects');
        $query->addAggregation($subjectAggregation);

        $query->setSize(0);



        return $query;
    }


    private function getCountQuery($journal)
    {
        $filter = new Term();
        $filter->setTerm('journal.id',$journal->getId());
        $filterQuery = new Query\Filtered();
        $filterQuery->setFilter($filter);
        $query = new Query($filterQuery);
        $query->setSize(0);
        return $query;
    }
}
