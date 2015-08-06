<?php

namespace Ojs\SiteBundle\Controller;

use Elastica\Query;
use Elastica\Aggregation;
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
        $getInstitutions = $request->query->get('institution_filters');
        $typeFilters = !empty($getTypes) ? explode(',', $getTypes) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $institutionFilters = !empty($getInstitutions) ? explode(',', $getInstitutions) : [];

        $journalSearcher = $this->get('fos_elastica.index.search.journal');
        $boolQuery = new Query\Bool();

        $match = new Query\Match();
        $match->setField('status', 1);
        $boolQuery->addMust($match);

        $match = new Query\Match();
        $match->setField('published', true);
        $boolQuery->addMust($match);

        if (!empty($typeFilters) || !empty($subjectFilters) || !empty($institutionFilters)) {

            foreach ($typeFilters as $type) {
                $match = new Query\Match();
                $match->setField('institution.institution_type.name', $type);
                $boolQuery->addMust($match);
            }

            foreach ($subjectFilters as $subject) {
                $match = new Query\Match();
                $match->setField('subjects.subject', $subject);
                $boolQuery->addMust($match);
            }

            foreach ($institutionFilters as $institution) {
                $match = new Query\Match();
                $match->setField('institution.name', $institution);
                $boolQuery->addMust($match);
            }
        }

        $journalQuery = new Query($boolQuery);

        $typeAgg = new Aggregation\Terms('types');
        $typeAgg->setField('institution.institution_type.name');
        $typeAgg->setOrder('_term', 'asc');
        $typeAgg->setSize(0);
        $journalQuery->addAggregation($typeAgg);

        $subjectAgg = new Aggregation\Terms('subjects');
        $subjectAgg->setField('subjects.subject');
        $subjectAgg->setOrder('_term', 'asc');
        $subjectAgg->setSize(0);
        $journalQuery->addAggregation($subjectAgg);

        $institutionAgg = new Aggregation\Terms('institutions');
        $institutionAgg->setField('institution.name');
        $institutionAgg->setOrder('_term', 'asc');
        $institutionAgg->setSize(0);
        $journalQuery->addAggregation($institutionAgg);

        $adapter = new ElasticaAdapter($journalSearcher, $journalQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(21);
        $pagerfanta->setCurrentPage($page);
        $journals = $pagerfanta->getCurrentPageResults();

        $types = $adapter->getResultSet()->getAggregation('types')['buckets'];
        $subjects = $adapter->getResultSet()->getAggregation('subjects')['buckets'];
        $institutions = $adapter->getResultSet()->getAggregation('institutions')['buckets'];

        $data = [
            'types' => $types,
            'subjects' => $subjects,
            'institutions' => $institutions,
            'type_filters' => $typeFilters,
            'subject_filters' => $subjectFilters,
            'institution_filters' => $institutionFilters,
            'journals' => $journals,
            'pagerfanta' => $pagerfanta
        ];

        return $this->render('OjsSiteBundle:Explore:index.html.twig', $data);
    }
}
