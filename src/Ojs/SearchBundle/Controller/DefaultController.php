<?php

namespace Ojs\SearchBundle\Controller;

use Elastica\Exception\NotFoundException;
use Elastica\Index;
use \Elastica\Query;
use Elastica\Query\Bool;
use \Elastica\Query\MoreLikeThis;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param  Request $request
     * @param  int $page
     * @return Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $data = [];
        $term = $request->get('q');
        $filter = $request->get('filter', []);
        $searchManager = $this->get('ojs_search_manager');
        $searchManager->addParam('term', $term);
        $searchManager->setPage($page);
        $searchManager->addFilters($filter);
        $result = $searchManager->search()->getResult();
        $data['pager'] = $searchManager->getPager();
        $data['result'] = $result;
        $data['total_count'] = $searchManager->getCount();
        $data['page'] = $page;
        $data['page_count'] = $searchManager->getPageCount();
        $data['term'] = $term;
        $data['aggregations'] = $searchManager->getAggregations();
        $data['filter'] = $filter;

        return $this->render('OjsSearchBundle:Search:index.html.twig', $data);
    }

    /**
     *
     * @param $tag
     * @param  int $page
     * @return Response
     */
    public function tagAction($tag, $page = 1)
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

        $data['tag'] = $tag;
        $data['total_count'] = $searchManager->getCount();

        return $this->render('OjsSearchBundle:Search:tags.html.twig', $data);
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

    public function advancedAction()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $mapping = $search->getMapping();
        return $this->render("OjsSearchBundle:Search:advanced.html.twig", [
            'mapping' => $mapping
        ]);
    }

    public function advancedResultAction(Request $request)
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
        $term = $request->get('term');
        if (empty($term))
            throw new NotFoundException('You must specify an term to search!');
        $parseQuery =$searchManager->parseSearchQuery($term);
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
        return $this->render("OjsSearchBundle:Search:advanced_result.html.twig", [
            'results' => $return_data,
            'searchQuery' => $term,
            'total_count' => $data->getTotalHits()
        ]);
    }
}
