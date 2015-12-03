<?php

namespace Ojs\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use Elastica\Query;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Publisher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PublicSearchRest may contain similar actions with SearchRest
 */
class PublicSearchRestController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="search Publishers",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/public/search/tags")
     *
     * @param  Request $request
     * @return array
     */
    public function getTagsAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('tags', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            foreach (explode(',', $result->getData()['tags']) as $tag) {
                $data[] = ['id' => $tag, 'text' => $tag];
            }
        }

        return $data;
    }
}
