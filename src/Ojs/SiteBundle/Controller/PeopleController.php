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
        $filters = $request->query->get('filters', array());
        $searcher = $this->get('fos_elastica.index.search.user');
        $query = new Query();

        if(!empty($filters)) {
            $bool = new Query\Bool();

            if (array_key_exists('subjects', $filters)) {
                foreach ($filters['subjects'] as $subject) {
                    $match = new Query\Match();
                    $match->setField('subjects', $subject);
                    $bool->addMust($match);
                }
            }

            $query->setQuery($bool);
        }

        $aggregation = new Terms('subject');
        $aggregation->setField('subjects');
        $query->addAggregation($aggregation);

        $search = $searcher->search($query);
        $results = $search->getResults();
        // $subjects = $search->getAggregation('subject')['buckets'];
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
            'filters' => $filters,
            'people' => $people,
            // 'subjects' => $subjects,
            'subjects' => $subjects,
            'pagerfanta' => $pagerfanta,
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}
