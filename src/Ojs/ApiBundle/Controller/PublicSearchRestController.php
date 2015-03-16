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
use Symfony\Component\HttpFoundation\Request;

/**
 * PublicSearchRest may contain similar actions with SearchRest
 */
class PublicSearchRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="search Institutions",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      },
     *      {
     *          "name"="verified",
     *          "dataType"="boolean",
     *          "required"="false",
     *          "description"="list only verified or not"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/public/search/institution")
     */
    public function getInstitutionsAction(Request $request)
    {
        $limit = $request->get('limit');
        $verified = $request->get('verified');
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.institution');

        $query = new Query\Bool();
        $q1 = new Query\Regexp('name', $q);
        $query->addShould($q1);
        $q2 = new Query\Regexp('tags', $q);
        $query->addShould($q2);


        if ($verified) {
            $query2 = new Query\Bool();
            $query2->addMust($query);
            $must2 = new Query\Match();
            $must2->setField('verified', $verified);
            $query2->addMust($must2);
        }

        $results = $search->search($verified?$query2:$query);
        $data = [];
        foreach ($results as $result) {
            $data[] = array_merge(array('id' => $result->getId()), $result->getData());
        }
        return $data;
    }

}
