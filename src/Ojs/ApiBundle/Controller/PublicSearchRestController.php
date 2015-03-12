<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as RestView;
 
use Elastica\Query;   
use FOS\ElasticaBundle\Doctrine\ORM\ElasticaToModelTransformer;

class PublicSearchRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="search Institutions"
     * )
     * @Get("/public/search/institution/{q}/{page}/{limit}")
     */
    public function getInstitutionsAction($q, $page = 0, $limit = 50)
    {
        $search = $this->container->get('fos_elastica.index.search.institution');
        $query = new Query\Bool();
        $should = new Query\Match();
        $should->setField('name', $q);  
        $query->addShould($should); 
         $should2 = new Query\Match();
        $should2->setField('tags', $q);
        $query->addShould($should2); 
        $results = $search->search($query);
       $data = [];
        foreach ($results as $result) {
            $data[] = array_merge(array('id' => $result->getId()), $result->getData());
        }
        return $data;
    }

}
