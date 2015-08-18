<?php
namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class AnalyticsRestController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Increment object view count",
     *  requirements={
     *      {
     *          "name"="page_url",
     *          "dataType"="string",
     *          "description"="Requested page url"
     *      }
     *  }
     * )
     * @Put("/stats/view/{entity}/{id}")
     *
     * @param  Request $request
     * @param $id
     * @param $entity
     * @return null
     */
    public function putObjectViewAction(Request $request, $id, $entity)
    {

        return null;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get object total Views"
     * )
     * @Get("/stats/view/{entity}/{id}/")
     *
     * @param $id
     * @param $entity
     * @return null
     */
    public function getObjectViewAction($id, $entity)
    {

        return null;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Increment object download count"
     * )
     * @Put("/stats/download/{entity}/{id}/")
     *
     * @param  Request $request
     * @param $id
     * @param $entity
     * @return null
     */
    public function putObjectDownloadAction(Request $request, $id, $entity)
    {

        return null;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get object total download count"
     * )
     * @Get("/stats/download/{entity}/{id}/")
     *
     * @param  Request $request
     * @param $id
     * @param $entity
     * @return null
     */
    public function getObjectDownloadAction(Request $request, $id, $entity)
    {

        return null;
    }
}