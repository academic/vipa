<?php

namespace Ojs\SearchBundle\Controller;

use Elastica\Exception\NotFoundException;
use Elastica\Index;
use \Elastica\Query;
use Elastica\Query\Bool;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Elastica\ResultSet;
use Elastica\Aggregation;

class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
        $queryType = $request->query->has('type')?$request->get('type'): 'basic';
        $query = $request->get('q');
        $section = $request->get('section');

        if($queryType == 'basic'){

            $data = $this->basicSearch($request,$query, $page);
        }elseif($queryType == 'advanced'){

            $data = $this->advancedSearch($request,$query, $page);
        }elseif($queryType == 'tag'){

            $data = $this->tagSearch($request,$query, $page);
        }
        $this->addQueryToHistory($request, $query, $queryType, $data['total_count']);
        if(empty($section)){
            $section = array_keys($data['results'])[0];
            $redirectParams = array_merge($request->query->all(), ['section' => $section]);
            return $this->redirectToRoute('ojs_search_index', $redirectParams);
        }else{
            $data['section'] = $section;
        }
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
        $getRoles = $request->query->get('role_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getJournals = $request->query->get('journal_filters');
        $roleFilters = !empty($getRoles) ? explode(',', $getRoles) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $journalFilters = !empty($getJournals) ? explode(',', $getJournals) : [];

        $searcher = $this->get('fos_elastica.index.search');
        $searchQuery = new Query('_all');

        $boolQuery = new Query\Bool();

        $fieldQuery = new Query\Prefix();
        $fieldQuery->setPrefix('_all', $query);
        $boolQuery->addMust($fieldQuery);

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
        $searchQuery->setQuery($boolQuery);

        $roleAgg = new Aggregation\Terms('roles');
        $roleAgg->setField('userJournalRoles.role.name');
        $roleAgg->setOrder('_term', 'asc');
        $roleAgg->setSize(0);
        $searchQuery->addAggregation($roleAgg);

        $subjectAgg = new Aggregation\Terms('subjects');
        $subjectAgg->setField('subjects');
        $subjectAgg->setOrder('_term', 'asc');
        $subjectAgg->setSize(0);
        $searchQuery->addAggregation($subjectAgg);

        $journalAgg = new Aggregation\Terms('journals');
        $journalAgg->setField('userJournalRoles.journal.title');
        $journalAgg->setOrder('_term', 'asc');
        $journalAgg->setSize(0);
        $searchQuery->addAggregation($journalAgg);

        $resultData = $searcher->search($searchQuery);

        $roles = $resultData->getAggregation('roles')['buckets'];
        $subjects = $resultData->getAggregation('subjects')['buckets'];
        $journals = $resultData->getAggregation('journals')['buckets'];

        $return_data = [];
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
        $data = [
            'results' => $return_data,
            'query' => $query,
            'queryType' => 'basic',
            'total_count' => $resultData->count(),
            'roles' => $roles,
            'subjects' => $subjects,
            'journals' => $journals,
            'role_filters' => $roleFilters,
            'subject_filters' => $subjectFilters,
            'journal_filters' => $journalFilters,
        ];
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
