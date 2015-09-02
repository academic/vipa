<?php

namespace Ojs\SiteBundle\Controller;

use Elastica\Aggregation;
use Elastica\Query;
use Pagerfanta\Adapter\ElasticaAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExploreController extends Controller
{
    public function indexAction(Request $request, $page = 1)
    {
        $getTypes = $request->query->get('type_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getPublishers = $request->query->get('publisher_filters');
        $typeFilters = !empty($getTypes) ? explode(',', $getTypes) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $publisherFilters = !empty($getPublishers) ? explode(',', $getPublishers) : [];

        $journalSearcher = $this->get('fos_elastica.index.search.journal');
        $boolQuery = new Query\Bool();

        $match = new Query\Match();
        $match->setField('status', 1);
        $boolQuery->addMust($match);

        $match = new Query\Match();
        $match->setField('published', true);
        $boolQuery->addMust($match);

        if (!empty($typeFilters) || !empty($subjectFilters) || !empty($publisherFilters)) {

            foreach ($typeFilters as $type) {
                $match = new Query\Match();
                $match->setField('publisher.publisher_type.name', $type);
                $boolQuery->addMust($match);
            }

            foreach ($subjectFilters as $subject) {
                $match = new Query\Match();
                $match->setField('subjects.subject', $subject);
                $boolQuery->addMust($match);
            }

            foreach ($publisherFilters as $publisher) {
                $match = new Query\Match();
                $match->setField('publisher.name', $publisher);
                $boolQuery->addMust($match);
            }
        }

        $journalQuery = new Query($boolQuery);

        $typeAgg = new Aggregation\Terms('types');
        $typeAgg->setField('publisher.publisher_type.name');
        $typeAgg->setOrder('_term', 'asc');
        $typeAgg->setSize(0);
        $journalQuery->addAggregation($typeAgg);

        $subjectAgg = new Aggregation\Terms('subjects');
        $subjectAgg->setField('subjects.subject');
        $subjectAgg->setOrder('_term', 'asc');
        $subjectAgg->setSize(0);
        $journalQuery->addAggregation($subjectAgg);

        $publisherAgg = new Aggregation\Terms('publishers');
        $publisherAgg->setField('publisher.name');
        $publisherAgg->setOrder('_term', 'asc');
        $publisherAgg->setSize(0);
        $journalQuery->addAggregation($publisherAgg);

        $adapter = new ElasticaAdapter($journalSearcher, $journalQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(21);
        $pagerfanta->setCurrentPage($page);
        $journals = $pagerfanta->getCurrentPageResults();

        $types = $adapter->getResultSet()->getAggregation('types')['buckets'];
        $subjects = $adapter->getResultSet()->getAggregation('subjects')['buckets'];
        $publishers = $adapter->getResultSet()->getAggregation('publishers')['buckets'];

        $data = [
            'types' => $types,
            'subjects' => $subjects,
            'publishers' => $publishers,
            'type_filters' => $typeFilters,
            'subject_filters' => $subjectFilters,
            'publisher_filters' => $publisherFilters,
            'journals' => $journals,
            'pagerfanta' => $pagerfanta,
            'page' => 'explore'
        ];

        return $this->render('OjsSiteBundle:Explore:index.html.twig', $data);
    }
}
