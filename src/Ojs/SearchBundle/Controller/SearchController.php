<?php

namespace Ojs\SearchBundle\Controller;

use Elastica\Index;
use Elastica\Query;
use Elastica\ResultSet;
use Elastica\Aggregation;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;

class SearchController extends Controller
{
    /**
     * search page index controller
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        /**
         * @var \Ojs\SearchBundle\Manager\SearchManager $searchManager
         */
        $searchManager = $this->get('ojs_search_manager');

        $getRoles = $request->query->get('role_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getJournals = $request->query->get('journal_filters');
        $roleFilters = !empty($getRoles) ? explode(',', $getRoles) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $journalFilters = !empty($getJournals) ? explode(',', $getJournals) : [];

        $queryType = $request->query->has('type') ? $request->get('type') : 'basic';
        $query = $request->get('q');

        $section = $request->get('section');

        $searcher = $this->get('fos_elastica.index.search');
        $searchQuery = new Query('_all');
        $boolQuery = new Query\Bool();

        //set query according to query type
        if ($queryType == 'basic') {

            $fieldQuery = new Query\MultiMatch();
            $fieldQuery->setFields(['_all']);
            $fieldQuery->setType('phrase_prefix');
            $fieldQuery->setQuery($query);
            $boolQuery->addMust($fieldQuery);
        } elseif ($queryType == 'advanced') {

            $parseQuery = $searchManager->parseSearchQuery($query);
            foreach ($parseQuery as $searchTerm) {
                $condition = $searchTerm['condition'];
                $advancedFieldQuery = new Query\MultiMatch();
                $advancedFieldQuery->setFields(
                    [$searchTerm['searchField']]
                );
                $advancedFieldQuery->setType('phrase_prefix');
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

            $matchQuery = new Query\Match();
            $matchQuery->setField('tags', $query);
            $boolQuery->addMust($matchQuery);
        }

        //set aggregations if requested
        if (!empty($roleFilters) || !empty($subjectFilters) || !empty($journalFilters)) {

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
        $subjectAgg->setField('subjects');
        $subjectAgg->setOrder('_term', 'asc');
        $subjectAgg->setSize(0);
        $searchQuery->addAggregation($subjectAgg);

        //get journal aggregation
        $journalAgg = new Aggregation\Terms('journals');
        $journalAgg->setField('userJournalRoles.journal.title');
        $journalAgg->setOrder('_term', 'asc');
        $journalAgg->setSize(0);
        $searchQuery->addAggregation($journalAgg);

        /**
         * @var ResultSet $resultData
         */
        $resultData = $searcher->search($searchQuery);

        $roles = $resultData->getAggregation('roles')['buckets'];
        $subjects = $resultData->getAggregation('subjects')['buckets'];
        $journals = $resultData->getAggregation('journals')['buckets'];

        $results = [];
        if($resultData->count()>0){
            /**
             * manipulate result data for easily use on template
             */
            $results = $searchManager->buildResultsObject($resultData, $section);
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
            $pagerfanta->setMaxPerPage(10);
            $pagerfanta->setCurrentPage($page);
            $results[$section]['data'] = $pagerfanta->getCurrentPageResults();
            var_dump($results[$section]['data']);exit();
        }
        /**
         * add search query to query history
         * history data stores on session
         */
        $this->addQueryToHistory($request, $query, $queryType, $resultData->count());
        $data = [
            'results' => $results,
            'query' => $query,
            'queryType' => $queryType,
            'section' => $section,
            'total_count' => $resultData->count(),
            'roles' => $roles,
            'subjects' => $subjects,
            'journals' => $journals,
            'role_filters' => $roleFilters,
            'subject_filters' => $subjectFilters,
            'journal_filters' => $journalFilters,
            'pagerfanta' => $pagerfanta,
        ];
        return $this->render('OjsSearchBundle:Search:index.html.twig', $data);
    }

    /**
     * store query to query history for future searches
     * @param Request $request
     * @param $query
     * @param $queryType
     * @param $totalCount
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
        return $this->render("OjsSearchBundle:Search:advanced.html.twig", [
            'mapping' => $mapping
        ]);
    }

    /**
     * Tag cloud page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagCloudAction()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $searchQuery = new Query('_all');

        //get tags aggregation
        $tagsAgg = new Aggregation\Terms('tags');
        $tagsAgg->setField('tags');
        $tagsAgg->setOrder('_term', 'asc');
        $tagsAgg->setSize(0);
        $searchQuery->addAggregation($tagsAgg);
        /**
         * @var ResultSet $results
         */
        $results = $search->search($searchQuery);

        $data['tags'] = [];
        foreach ($results->getAggregations()['tags']['buckets'] as $result) {
            $data['tags'][] = $result['key'];
        }
        return $this->render('OjsSearchBundle:Search:tags_cloud.html.twig', $data);
    }
}
