<?php

namespace Ojs\SearchBundle\Controller;

 use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param  Request  $request
     * @param  int      $page
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

        return $this->render('OjsSiteBundle:Search:index.html.twig', $data);
    }

    /**
     *
     * @param $tag
     * @param  int      $page
     * @return Response
     */
    public function tagAction($tag, $page = 1)
    {
        $data = [];
        $searchManager = $this->get('ojs_search_manager');
        $searchManager->addParam('term', $tag);
        $searchManager->setPage($page);
        $result = $searchManager->tagSearch();
        $data['results'] = $result;

        $data['tag'] = $tag;
        $data['total_count'] = $searchManager->getCount();

        return $this->render('OjsSiteBundle:Search:tags.html.twig', $data);
    }
}
