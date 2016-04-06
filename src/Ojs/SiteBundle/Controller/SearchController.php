<?php

namespace Ojs\SiteBundle\Controller;

use Elastica\Aggregation;
use Elastica\Query;
use Elastica\ResultSet;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $sm = $this->get('ojs_core.search_manager');
        $sm
            ->setupRequestAggs()
            ->setupSection()
            ->setPage($page)
            ->setupQuery()
            ;

        if($sm->getSection() === null){
            $section = $sm->decideSection();
            if($section == null){
                return new Response('No result found');
            }
            return $this->redirectToRoute('ojs_search_index', array_merge($request->query->all(), ['section' => $section]));
        }

        $resultSet = $sm->getQueryResultSet();










        if ($resultData->count() > 0) {

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
