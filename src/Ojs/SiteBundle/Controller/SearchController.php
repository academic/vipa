<?php

namespace Ojs\SiteBundle\Controller;

use Elastica\Aggregation;
use Elastica\Query;
use Elastica\ResultSet;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * search page index controller
     *
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $searchManager = $this->get('ojs_core.search_manager');

        $journalId = null;

        $getRoles = $request->query->get('role_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getJournals = $request->query->get('journal_filters');
        $getLocales = $request->query->get('locale_filters');
        $getPublishers = $request->query->get('publisher_filters');
        $getIndexes = $request->query->get('index_filters');
        $roleFilters = !empty($getRoles) ? explode(',', $getRoles) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $journalFilters = !empty($getJournals) ? explode(',', $getJournals) : [];
        $localeFilters = !empty($getLocales) ? explode(',', $getLocales) : [];
        $publisherFilters = !empty($getPublishers) ? explode(',', $getPublishers) : [];
        $indexFilters = !empty($getIndexes) ? explode(',', $getIndexes) : [];

        $queryType = $request->query->has('type') ? $request->get('type') : 'basic';

        $query = filter_var($request->get('q'), FILTER_SANITIZE_STRING);

        $section = filter_var($request->get('section'), FILTER_SANITIZE_STRING);

        $searcher = $this->get('fos_elastica.index.search');

        $searchQuery = new Query('_all');

        $boolQuery = new Query\BoolQuery();

        //set query according to query type
        if ($queryType == 'basic') {
            $request->getLocale() === 'tr' ? $searchString = str_replace(['I', 'İ'], ['ı', 'i'], $query) : $searchString = $query;
            $searchString = sprintf('%s', mb_strtolower($searchString, 'UTF-8'));

            $fieldQuery = new Query\MatchPhrasePrefix();
            $fieldQuery->setParam('_all', $searchString);
            $boolQuery->addMust($fieldQuery);
        } elseif ($queryType == 'advanced') {

            $parseQuery = $searchManager->parseSearchQuery($query);
            foreach ($parseQuery as $searchTerm) {
                $condition = $searchTerm['condition'];
                $advancedFieldQuery = new Query\MultiMatch();
                $advancedFieldQuery->setFields(
                    [$searchTerm['searchField']]
                );
                $advancedFieldQuery->setQuery($searchTerm['searchText']);
                if ($condition == 'AND') {
                    $boolQuery->addMust($advancedFieldQuery);
                } elseif ($condition == 'OR') {
                    $boolQuery->addShould($advancedFieldQuery);
                } elseif ($condition == 'NOT') {
                    $boolQuery->addMustNot($advancedFieldQuery);
                }
            }
        } elseif ($queryType == 'tag') {

            $regexpQuery = new Query\Regexp();
            $regexpQuery->setParams(['tags' => ".*".$query.".*"]);
            $boolQuery->addMust($regexpQuery);
        } elseif ($queryType == 'injournal') {

            $journalId = $request->get('journalId');
            $boolQuery->setParams($searchManager->getSearchInJournalQuery($journalId, $query));
        }

        //set aggregations if requested
        if (!empty($roleFilters) 
            || !empty($subjectFilters) 
            || !empty($journalFilters) 
            || !empty($localeFilters) 
            || !empty($publisherFilters)
        ) {
            foreach ($roleFilters as $role) {
                $match = new Query\Match();
                $match->setField('user.userJournalRoles.role.name', $role);
                $boolQuery->addMust($match);
            }

            foreach ($subjectFilters as $subject) {
                $match = new Query\Match();
                $match->setField('subjects', $subject);
                $boolQuery->addMust($match);
            }

            foreach ($journalFilters as $journal) {
                $match = new Query\Match();
                $match->setField('user.userJournalRoles.journal.title', $journal);
                $boolQuery->addMust($match);
            }

            foreach ($localeFilters as $locale) {
                $match = new Query\Match();
                $match->setField('translations.locale', $locale);
                $boolQuery->addMust($match);
            }

            foreach ($publisherFilters as $publisher) {
                $match = new Query\Match();
                $match->setField('publisher.name', $publisher);
                $boolQuery->addMust($match);
            }

            foreach ($indexFilters as $index) {
                $match = new Query\Match();
                $match->setField('journalIndexs.index.name', $index);
                $boolQuery->addMust($match);
            }
        }
        
        //set our boolean query
        $searchQuery->setQuery($boolQuery);
        //get all result
        $searchQuery->setSize(1000);

        //get role aggregation
        $roleAgg = new Aggregation\Terms('roles');
        $roleAgg->setField('userJournalRoles.role.name');
        $roleAgg->setOrder('_term', 'asc');
        $roleAgg->setSize(0);
        $searchQuery->addAggregation($roleAgg);

        //get subject aggregation
        $subjectAgg = new Aggregation\Terms('subjects');
        $subjectAgg->setField('subjects.subject');
        $subjectAgg->setOrder('_term', 'asc');
        $subjectAgg->setSize(0);
        $searchQuery->addAggregation($subjectAgg);

        //get journal aggregation
        $journalAgg = new Aggregation\Terms('journals');
        $journalAgg->setField('journal.title.raw');
        $journalAgg->setOrder('_term', 'asc');
        $journalAgg->setSize(0);
        $searchQuery->addAggregation($journalAgg);

        $localeAgg = new Aggregation\Terms('locales');
        $localeAgg->setField('translations.locale');
        $localeAgg->setOrder('_term', 'asc');
        $localeAgg->setSize(0);
        $searchQuery->addAggregation($localeAgg);

        $publisherAgg = new Aggregation\Terms('publishers');
        $publisherAgg->setField('publisher.name.raw');
        $publisherAgg->setOrder('_term', 'asc');
        $publisherAgg->setSize(0);
        $searchQuery->addAggregation($publisherAgg);

        $indexAgg = new Aggregation\Terms('indexes');
        $indexAgg->setField('journalIndexs.index.name.raw');
        $indexAgg->setOrder('_term', 'asc');
        $indexAgg->setSize(0);
        $searchQuery->addAggregation($indexAgg);

        /**
         * @var ResultSet $resultData
         */
        $resultData = $searcher->search($searchQuery);

        $roles = $resultData->getAggregation('roles')['buckets'];
        $subjects = $resultData->getAggregation('subjects')['buckets'];
        $journals = $resultData->getAggregation('journals')['buckets'];
        $locales = $resultData->getAggregation('locales')['buckets'];
        $publishers = $resultData->getAggregation('publishers')['buckets'];
        $indexes = $resultData->getAggregation('indexes')['buckets'];

        if ($resultData->count() > 0) {
            /**
             * manipulate result data for easily use on template
             */
            $results = $searchManager->buildResultsObject($resultData, $section);
            $results = $searchManager->reOrderResultObjects($results);
            /**
             * if search section is not defined or empty redirect to first result section
             */
            if (empty($section)) {
                $section = array_keys($results)[0];
                $redirectParams = array_merge($request->query->all(), ['section' => $section]);

                return $this->redirectToRoute('ojs_search_index', $redirectParams);
            } else {
                /**
                 * if section result is empty redirect to first that have result section
                 */
                if (!isset($results[$section])) {
                    foreach ($results as $resultKey => $result) {
                        if ($result['total_item'] > 0) {

                            $redirectParams = array_merge($request->query->all(), ['section' => $resultKey]);

                            return $this->redirectToRoute('ojs_search_index', $redirectParams);
                        }
                    }
                }
            }
            $adapter = new ArrayAdapter($results[$section]['data']);
            $pagerfanta = new Pagerfanta($adapter);
            $pagerfanta->setMaxPerPage(20);
            $pagerfanta->setCurrentPage($page);
            $results[$section]['data'] = $pagerfanta->getCurrentPageResults();
            /**
             * add search query to query history
             * history data stores on session
             */
            $this->addQueryToHistory($request, $query, $queryType, $resultData->count());

            $data = [
                'journalId'         => $journalId,
                'results'           => $results,
                'query'             => $query,
                'queryType'         => $queryType,
                'section'           => $section,
                'total_count'       => $searchManager->getTotalHit(),
                'roles'             => $roles,
                'subjects'          => $subjects,
                'locales'           => $locales,
                'journals'          => $journals,
                'publishers'        => $publishers,
                'indexes'           => $indexes,
                'role_filters'      => $roleFilters,
                'subject_filters'   => $subjectFilters,
                'journal_filters'   => $journalFilters,
                'locale_filters'    => $localeFilters,
                'publisher_filters' => $publisherFilters,
                'index_filters'     => $indexFilters,
                'pagerfanta'        => $pagerfanta,
                'page'              => $page
            ];

        } else {
            $data = [
                'journalId' => $journalId,
                'query' => $query,
                'queryType' => $queryType,
                'total_count' => $searchManager->getTotalHit(),
                'journals' => []
            ];
        }
        return $this->render('OjsSiteBundle:Search:index.html.twig', $data);
    }

    /**
     * store query to query history for future searches
     * @param Request $request
     * @param $query
     * @param $queryType
     * @param integer $totalCount
     * @return bool
     */
    private function addQueryToHistory(Request $request, $query, $queryType, $totalCount)
    {
        $session = $request->getSession();
        if (!$session->has('_query_history')) {
            $session->set('_query_history', []);
        }
        $queryHistory = $session->get('_query_history');
        $queryCount = count($queryHistory);
        $setQuery['type'] = $queryType;
        $setQuery['time'] = date("H:i:s");
        $setQuery['id'] = $queryCount + 1;
        $setQuery['query'] = $query;
        $setQuery['totalHits'] = $totalCount;
        $queryHistory[] = $setQuery;
        $session->set('_query_history', $queryHistory);

        return true;
    }

    /**
     * advanced search builder page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advancedAction()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $mapping = $search->getMapping();

        return $this->render(
            "OjsSiteBundle:Search:advanced.html.twig",
            [
                'mapping' => $mapping
            ]
        );
    }

    /**
     * Tag cloud page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagCloudAction()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $searchQuery = new Query();
        $searchQuery->setSize(0);

        //get tags aggregation
        $tagsAgg = new Aggregation\Terms('tags');
        $tagsAgg->setField('tags');
        $tagsAgg->setSize(500);
        $searchQuery->addAggregation($tagsAgg);
        /**
         * @var ResultSet $results
         */
        $results = $search->search($searchQuery);

        $data['tags'] = [];
        foreach ($results->getAggregations()['tags']['buckets'] as $result) {
            $keys = array_filter(explode(',', $result['key']));
            if(is_array($keys)){
                foreach($keys as $key){
                    $data['tags'][] = trim($key);
                }
            }
        }

        return $this->render('OjsSiteBundle:Search:tags_cloud.html.twig', $data);
    }
}
