<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Pagerfanta\Adapter\ElasticaAdapter;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Pagerfanta;
use Elastica\Query;
use Elastica\Aggregation\Terms;

class PeopleController extends Controller
{
    /**
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $getQuery = $request->query->get('filters');
        $filters = !empty($getQuery) ?  explode(',', $getQuery) : null;

        $searcher = $this->get('fos_elastica.index.search.user');
        $searchQuery = new Query();

        if(!empty($filters)) {
            $bool = new Query\Bool();
            foreach ($filters as $subject) {
                $match = new Query\Match();
                $match->setField('subjects', $subject);
                $bool->addMust($match);
            }

            $searchQuery->setQuery($bool);
        }

        $aggregation = new Terms('subject');
        $aggregation->setField('subjects');
        $searchQuery->addAggregation($aggregation);
        $subjects = $searcher->search($searchQuery)->getAggregation('subject')['buckets'];

        $adapter = new ElasticaAdapter($searcher, $searchQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $people = $pagerfanta->getCurrentPageResults();

        $data = [
            'people' => $people,
            'subjects' => $subjects,
            'filters' => $filters,
            'pagerfanta' => $pagerfanta,
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}
