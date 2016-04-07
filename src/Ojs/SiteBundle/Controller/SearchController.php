<?php

namespace Ojs\SiteBundle\Controller;

use Elastica\Aggregation;
use Elastica\Query;
use Elastica\ResultSet;
use Ojs\CoreBundle\Controller\OjsController as Controller;
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
        $sm = $this->get('ojs_core.search_manager');

        //setup basic data
        $sm
            ->setupRequestAggs()
            ->setupSection()
            ->setPage($page)
            ->setupQuery()
            ;

        //if section is not specified
        if($sm->getSection() === null){
            //decide to section
            $section = $sm->decideSection();
            //if section is decided decided redirect to this section
            if($section !== null){
                return $this->redirectToRoute('ojs_search_index', array_merge($request->query->all(), ['section' => $section]));
            }
        }
        //build query result
        $sm->setupQueryResultSet();
        /**
         * if there is result but section result is not exists
         * redirect to main search system for decide correct section
         */
        if(count($sm->getResultSet()) > 0 && !isset($sm->getResultSet()[$sm->getSection()])){
            return $this->redirectToRoute('ojs_search_index', ['q' => $sm->getQuery()]);
        }

        return $this->render('OjsSiteBundle:Search:index.html.twig', ['sm' => $sm]);
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
        $sm = $this->get('ojs_core.search_manager');

        return $this->render(
            "OjsSiteBundle:Search:advanced.html.twig", ['fields' => $sm->getNativeQueryGenerator()->getSearchParamsBag()]);
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
