<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

use Elastica\Query;
use Elastica\Aggregation\Terms;

use FOS\ElasticaBundle\Doctrine\ORM\ElasticaToModelTransformer;

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

        $search = $searcher->search($searchQuery);
        $results = $search->getResults();
        $subjects = $this->getDoctrine()->getRepository('OjsJournalBundle:Subject')->findAll();

        $connection = $this->get('doctrine.orm.default_entity_manager')->getConnection();
        $manager = new Registry($this->container, ['default' => $connection],
            ['default' => 'doctrine.orm.entity_manager'], 'default', 'default');
        $transformer = new ElasticaToModelTransformer($manager, 'OjsUserBundle:User');
        $transformer->setPropertyAccessor($this->container->get('property_accessor'));
        $transformed = $transformer->transform($results);

        $adapter = new ArrayAdapter($transformed);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $people = $pagerfanta->getCurrentPageResults();

        $data = [
            'filters' => $getQuery,
            'people' => $people,
            'subjects' => $subjects,
            'pagerfanta' => $pagerfanta,
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}
