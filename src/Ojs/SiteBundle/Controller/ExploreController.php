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
        $getLocales = $request->query->get('locale_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getPublishers = $request->query->get('publisher_filters');
        $typeFilters = !empty($getTypes) ? explode(',', $getTypes) : [];
        $localeFilters = !empty($getLocales) ? explode(',', $getLocales) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $publisherFilters = !empty($getPublishers) ? explode(',', $getPublishers) : [];

        $journalSearcher = $this->get('fos_elastica.index.search.journal');
        $boolQuery = new Query\BoolQuery();

        if (!empty($typeFilters) || !empty($localeFilters) || !empty($subjectFilters) || !empty($publisherFilters)) {

            foreach ($typeFilters as $type) {
                $match = new Query\Match();
                $match->setField('journal.publisher.publisherType.name', $type);
                $boolQuery->addMust($match);
            }

            foreach ($subjectFilters as $subject) {
                $match = new Query\Match();
                $match->setField('subjects.subject', $subject);
                $boolQuery->addMust($match);
            }

            foreach ($publisherFilters as $publisher) {
                $match = new Query\Match();
                $match->setField('journal.publisher.name', $publisher);
                $boolQuery->addMust($match);
            }

            foreach ($localeFilters as $locale) {
                $match = new Query\Match();
                $match->setField('mandatoryLang', $locale);
                $boolQuery->addMust($match);
            }
        }

        $journalQuery = new Query($boolQuery);

        $typeAgg = new Aggregation\Terms('types');
        $typeAgg->setField('journal.publisher.publisherType.name');
        $typeAgg->setSize(0);
        $journalQuery->addAggregation($typeAgg);

        $localeAgg = new Aggregation\Terms('locales');
        $localeAgg->setField('mandatoryLang');
        $localeAgg->setSize(0);
        $journalQuery->addAggregation($localeAgg);

        $subjectAgg = new Aggregation\Terms('subjects');
        $subjectAgg->setField('subjects.subject');
        $subjectAgg->setSize(0);
        $journalQuery->addAggregation($subjectAgg);

        $publisherAgg = new Aggregation\Terms('publishers');
        $publisherAgg->setField('journal.publisher.name');
        $publisherAgg->setSize(0);
        $journalQuery->addAggregation($publisherAgg);

        $adapter = new ElasticaAdapter($journalSearcher, $journalQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $journals = $pagerfanta->getCurrentPageResults();

        $types = $adapter->getResultSet()->getAggregation('types')['buckets'];
        $locales = $adapter->getResultSet()->getAggregation('locales')['buckets'];
        $subjects = $adapter->getResultSet()->getAggregation('subjects')['buckets'];
        $publishers = $adapter->getResultSet()->getAggregation('publishers')['buckets'];

        $data = [
            'types'             => $types,
            'locales'           => $locales,
            'journals'          => $journals,
            'subjects'          => $subjects,
            'publishers'        => $publishers,
            'type_filters'      => $typeFilters,
            'locale_filters'    => $localeFilters,
            'subject_filters'   => $subjectFilters,
            'publisher_filters' => $publisherFilters,
            'pagerfanta'        => $pagerfanta,
            'page'              => 'explore',
            'search_section'    => 'journal',
        ];

        return $this->render('OjsSiteBundle:Explore:index.html.twig', $data);
    }

    public function publisherAction(Request $request, $page = 1)
    {
        $getTypes = $request->query->get('type_filters');
        $typeFilters = !empty($getTypes) ? explode(',', $getTypes) : [];

        $publisherSearcher = $this->get('fos_elastica.index.search.publisher');
        $boolQuery = new Query\BoolQuery();

        if (!empty($typeFilters)) {
            foreach ($typeFilters as $type) {
                $match = new Query\Match();
                $match->setField('publisherType', $type);
                $boolQuery->addMust($match);
            }
        }

        $publisherQuery = new Query($boolQuery);
        $publisherQuery->setSort(['name.raw' => ['order' => 'asc']]);

        $typeAgg = new Aggregation\Terms('types');
        $typeAgg->setField('publisher.publisherType.name');
        $typeAgg->setOrder('_term', 'asc');
        $typeAgg->setSize(0);
        $publisherQuery->addAggregation($typeAgg);

        $adapter = new ElasticaAdapter($publisherSearcher, $publisherQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $publishers = $pagerfanta->getCurrentPageResults();
        $types = $adapter->getResultSet()->getAggregation('types')['buckets'];

        $data = [
            'types'          => $types,
            'page'           => 'ojs_site_explore_publisher',
            'publishers'     => $publishers,
            'pagerfanta'     => $pagerfanta,
            'type_filters'   => $typeFilters,
            'search_section' => 'publisher',
        ];

        return $this->render('OjsSiteBundle:Explore:publisher.html.twig', $data);
    }
}
