<?php

namespace Ojs\SearchBundle\Controller;

use Elastica\Exception\NotFoundException;
use Elastica\Index;
use \Elastica\Query;
use Elastica\Query\Bool;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Elastica\ResultSet;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $page = 1)
    {
        $queryType = $request->query->has('type')?$request->get('type'): 'basic';
        $query = $request->get('q');

        if($queryType == 'basic'){

            $data = $this->basicSearch($request,$query, $page);
        }elseif($queryType == 'advanced'){

            $data = $this->advancedSearch($request,$query, $page);
        }elseif($queryType == 'tag'){

            $data = $this->tagSearch($request,$query, $page);
        }
        $this->addQueryToHistory($request, $query, $queryType, $data['total_count']);
        return $this->render('OjsSearchBundle:Search:index.html.twig', $data);
    }


    private function tagSearch(Request $request ,$tag, $page = 1)
    {
        $data = [];
        /**
         * @var \Ojs\SearchBundle\Manager\SearchManager $searchManager
         */
        $searchManager = $this->get('ojs_search_manager');
        $searchManager->addParam('term', $tag);
        $searchManager->setPage($page);
        $result = $searchManager->tagSearch();
        $data['results'] = $result;

        $data['query'] = $tag;
        $data['total_count'] = $searchManager->getCount();
        $data['queryType'] = 'tag';
        return $data;
    }

    private function basicSearch(Request $request,$query, $page)
    {
        /**
         * @var \Ojs\SearchBundle\Manager\SearchManager $searchManager
         */
        $searchManager = $this->get('ojs_search_manager');
        $finder = $this->container->get('fos_elastica.index.search');
        /**
         * @var ResultSet $resultData
         */
        $resultData = $finder->search($query);
        foreach ($resultData as $result) {
            /** @var Result $result */
            if (!isset($return_data[$result->getType()])) {
                $return_data[$result->getType()] = ['type', 'data'];
            }
            $return_data[$result->getType()]['type'] = $searchManager->getTypeText($result->getType());
            if (isset($return_data[$result->getType()]['data'])):
                $return_data[$result->getType()]['data'][] = $searchManager->getObject($result); else:
                $return_data[$result->getType()]['data'] = [$searchManager->getObject($result)];
            endif;
        }
        $data['results'] = $return_data;
        $data['query'] = $query;
        $data['queryType'] = 'basic';
        $data['total_count'] = $resultData->count();
        return $data;
    }

    private function advancedSearch(Request $request, $query, $page)
    {
        /**
         * @var \Ojs\SearchBundle\Manager\SearchManager $searchManager
         */
        $searchManager = $this->get('ojs_search_manager');
        /**
         * @var \FOS\ElasticaBundle\Elastica\Index $search
         */
        $search = $this->container->get('fos_elastica.index.search');
        $boolQuery = new Bool();
        if (empty($query))
            throw new NotFoundException('You must specify an term to search!');
        $parseQuery =$searchManager->parseSearchQuery($query);
        if(count($parseQuery)<1)
            throw new NotFoundException('We found anything!');

        foreach($parseQuery as $searchTerm){
            $condition = $searchTerm['condition'];
            $fieldQuery = new Query\Prefix();
            $fieldQuery->setPrefix($searchTerm['searchField'], $searchTerm['searchText']);
            if($condition == 'AND'){
                $boolQuery->addMust($fieldQuery);
            }elseif($condition == 'OR'){
                $boolQuery->addShould($fieldQuery);
            }elseif($condition == 'NOT'){
                $boolQuery->addMustNot($fieldQuery);
            }
        }
        /**
         * @var \Elastica\ResultSet $data
         */
        $data = $search->search($boolQuery);
        $return_data = [];

        foreach ($data as $result) {
            /** @var Result $result */
            if (!isset($return_data[$result->getType()])) {
                $return_data[$result->getType()] = ['type', 'data'];
            }
            $return_data[$result->getType()]['type'] = $searchManager->getTypeText($result->getType());
            if (isset($return_data[$result->getType()]['data'])):
                $return_data[$result->getType()]['data'][] = $searchManager->getObject($result); else:
                $return_data[$result->getType()]['data'] = [$searchManager->getObject($result)];
            endif;
        }
        $rData = [
            'results' => $return_data,
            'query' => $query,
            'total_count' => $data->getTotalHits(),
            'queryType' => 'advanced'
        ];
        return $rData;
    }

    private function addQueryToHistory(Request $request, $query, $queryType, $totalCount)
    {
        $session = $request->getSession();
        if(!$session->has('_query_history')){
            $session->set('_query_history', []);
        }
        $queryHistory = $session->get('_query_history');
        $queryCount = count($queryHistory);
        $setQuery['type'] = $queryType;
        $setQuery['time'] = date("H:i:s");
        $setQuery['id'] = $queryCount+1;
        $setQuery['query'] = $query;
        $setQuery['totalHits'] = $totalCount;
        $queryHistory[] = $setQuery;
        $session->set('_query_history', $queryHistory);
        return true;
    }

    public function advancedAction()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $mapping = $search->getMapping();
        return $this->render("OjsSearchBundle:Search:advanced.html.twig", [
            'mapping' => $mapping
        ]);
    }

    public function tagCloudAction()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $prefix = new Query\Prefix();
        $prefix->setPrefix('tags', '');
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data['tags'] = [];
        foreach ($results as $result) {
            foreach (explode(',', $result->getData()['tags']) as $tag) {
                $data['tags'][] = $tag;
            }
        }

        return $this->render('OjsSearchBundle:Search:tags_cloud.html.twig', $data);
    }
}
