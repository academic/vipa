<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\ReportBundle\Document\ObjectDownloads;
use Ojs\ReportBundle\Document\ObjectViews;
use Ojs\Common\Helper\StringHelper as StringHelper;
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
     * @param  Request     $request
     * @param $id
     * @param $entity
     * @return ObjectViews
     */
    public function putObjectViewAction(Request $request, $id, $entity)
    {
        $string_helper = new StringHelper();
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $stat = new ObjectViews();
        $stat->setPageUrl($string_helper->sanitize($request->get("page_url")));
        $stat->setIpAddress($request->getClientIp());
        $stat->setLogDate(new \DateTime("now"));
        $stat->setObjectId($id);
        $stat->setEntity($string_helper->sanitize($entity));
        $dm->persist($stat);
        $dm->flush();

        return $stat;
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
     * @return mixed
     */
    public function getObjectViewAction($id, $entity)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $data = $dm->getRepository('OjsReportBundle:ObjectView')
            ->findBy(['objectId' => $id, 'entity' => $entity]);

        return $data;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Increment object download count"
     * )
     * @Put("/stats/download/{entity}/{id}/")
     *
     * @param  Request         $request
     * @param $id
     * @param $entity
     * @return ObjectDownloads
     */
    public function putObjectDownloadAction(Request $request, $id, $entity)
    {
        $filePath = $request->get('file_path');
        $fileSize = $request->get('file_size') ? $request->get('file_size') : 0;
        $dm = $this->get('doctrine_mongodb')->getManager();
        $objectDownload = new ObjectDownloads();

        $objectDownload->setEntity($entity);
        $objectDownload->setFilePath($filePath);
        $objectDownload->setIpAddress($request->getClientIp());
        $objectDownload->setLogDate(new \DateTime("now"));
        $objectDownload->setObjectId($id);
        $objectDownload->setTransferSize($fileSize);
        $dm->persist($objectDownload);
        $dm->flush();

        return $objectDownload;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get object total download count"
     * )
     * @Get("/stats/download/{entity}/{id}/")
     *
     * @param  Request                                              $request
     * @param $id
     * @param $entity
     * @return array|\Ojs\ReportBundle\Document\ObjectDownload[]
     */
    public function getObjectDownloadAction(Request $request, $id, $entity)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $filePath = $request->get('file_path');
        $data = $dm->getRepository('OjsReportBundle:ObjectDownload')
            ->findBy(
                [
                    'objectId' => (int) $id, // very interesting {.-.} {-|-} {'-'}
                    'entity' => $entity,
                    'filePath' => $filePath,
                ]
            );

        return $data;
    }
}
