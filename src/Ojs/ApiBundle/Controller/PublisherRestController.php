<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublisherRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Publishers"
     * )
     * @Get("/publisher/bulk/{page}/{limit}")
     *
     * @param  int   $page
     * @param  int   $limit
     * @return mixed
     */
    public function getPublishersAction($page = 0, $limit = 10)
    {
        $publishers = $this->getDoctrine()->getManager()
            ->createQuery('SELECT i FROM OjsJournalBundle:Publisher i')
            ->setFirstResult($page)
            ->setMaxResults($limit)
            ->getResult();
        if (!is_array($publishers)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $publishers;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Specific Publisher"
     * )
     * @Get("/publisher/{id}")
     *
     * @param $id
     * @return object
     */
    public function getPublisherAction($id)
    {
        $publisher = $this->getDoctrine()->getRepository('OjsJournalBundle:Publisher')->find($id);
        if (!is_object($publisher)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $publisher;
    }
}
